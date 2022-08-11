<?php
declare(strict_types=1);

namespace SetBased\Stratum\MySql\Wrapper;

use SetBased\Stratum\MySql\Exception\MySqlQueryErrorException;

/**
 * Class for generating a wrapper method for a stored procedure that selects 0 or more rows with 2 columns. The rows are
 * returned as an array the first column are the keys and the second column are the values.
 */
class MapWrapper extends Wrapper
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getDocBlockReturnType(): string
  {
    return 'array';
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

    $this->codeStore->append('$result = $this->query(\'call '.$this->routine['routine_name'].'('.$routineArgs.')\');');
    $this->codeStore->append('$ret = [];');
    $this->codeStore->append('while (($row = $result->fetch_array(MYSQLI_NUM))) $ret[$row[0]] = $row[1];');
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
    $this->codeStore->append('$result = $stmt->get_result();');
    $this->codeStore->append('$ret = [];');
    $this->codeStore->append('while (($row = $result->fetch_array(MYSQLI_NUM))) $ret[$row[0]] = $row[1];');
    $this->codeStore->append('$result->free();');
    $this->codeStore->append('');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function writeRoutineFunctionLobReturnData(): void
  {
    $this->codeStore->append('return $ret;');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
