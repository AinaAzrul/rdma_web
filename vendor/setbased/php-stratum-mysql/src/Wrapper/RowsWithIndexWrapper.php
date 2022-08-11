<?php
declare(strict_types=1);

namespace SetBased\Stratum\MySql\Wrapper;

use SetBased\Stratum\MySql\Exception\MySqlDataLayerException;
use SetBased\Stratum\MySql\Exception\MySqlQueryErrorException;

/**
 * Class for generating a wrapper method for a stored procedure that selects 0 or more rows. The rows are returned as
 * nested arrays.
 */
class RowsWithIndexWrapper extends Wrapper
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getDocBlockReturnType(): string
  {
    return 'array[]';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getReturnTypeDeclaration(): string
  {
    return ': array';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function writeResultHandler(): void
  {
    $this->throws(MySqlQueryErrorException::class);

    $routineArgs = $this->getRoutineArgs();

    $index = '';
    foreach ($this->routine['index_columns'] as $column)
    {
      $index .= '[$row[\''.$column.'\']]';
    }

    $this->codeStore->append('$result = $this->query(\'call '.$this->routine['routine_name'].'('.$routineArgs.')\');');
    $this->codeStore->append('$ret = [];');
    $this->codeStore->append('while (($row = $result->fetch_array(MYSQLI_ASSOC))) $ret'.$index.'[] = $row;');
    $this->codeStore->append('$result->free();');
    $this->codeStore->append('if ($this->mysqli->more_results()) $this->mysqli->next_result();');
    $this->codeStore->append('');
    $this->codeStore->append('return $ret;');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function writeRoutineFunctionLobFetchData(): void
  {
    $index = '';
    foreach ($this->routine['index_columns'] as $column)
    {
      $index .= '[$new[\''.$column.'\']]';
    }

    $this->codeStore->append('$row = [];');
    $this->codeStore->append('$this->bindAssoc($stmt, $row);');
    $this->codeStore->append('');
    $this->codeStore->append('$ret = [];');
    $this->codeStore->append('while (($b = $stmt->fetch()))');
    $this->codeStore->append('{');
    $this->codeStore->append('$new = [];');
    $this->codeStore->append('foreach($row as $key => $value)');
    $this->codeStore->append('{');
    $this->codeStore->append('$new[$key] = $value;');
    $this->codeStore->append('}');
    $this->codeStore->append('$ret'.$index.'[] = $new;');
    $this->codeStore->append('}');
    $this->codeStore->append('');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function writeRoutineFunctionLobReturnData(): void
  {
    $this->throws(MySqlDataLayerException::class);

    $this->codeStore->append('if ($b===false) throw $this->dataLayerError(\'mysqli_stmt::fetch\');');
    $this->codeStore->append('');
    $this->codeStore->append('return $ret;');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
