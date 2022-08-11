<?php
declare(strict_types=1);

namespace SetBased\Stratum\MySql\Wrapper;

use SetBased\Stratum\Middle\Exception\ResultException;
use SetBased\Stratum\MySql\Exception\MySqlQueryErrorException;

/**
 * Class for generating a wrapper method for a stored procedure that selects 0 or 1 row.
 */
class Row0Wrapper extends Wrapper
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getDocBlockReturnType(): string
  {
    return 'array|null';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getReturnTypeDeclaration(): string
  {
    return ': ?array';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function writeResultHandler(): void
  {
    $this->throws(MySqlQueryErrorException::class);
    $this->throws(ResultException::class);

    $routineArgs = $this->getRoutineArgs();
    $this->codeStore->append('return $this->executeRow0(\'call '.$this->routine['routine_name'].'('.$routineArgs.')\');');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function writeRoutineFunctionLobFetchData(): void
  {
    $this->codeStore->append('$row = [];');
    $this->codeStore->append('$this->bindAssoc($stmt, $row);');
    $this->codeStore->append('');
    $this->codeStore->append('$tmp = [];');
    $this->codeStore->append('while (($b = $stmt->fetch()))');
    $this->codeStore->append('{');
    $this->codeStore->append('$new = [];');
    $this->codeStore->append('foreach($row as $key => $value)');
    $this->codeStore->append('{');
    $this->codeStore->append('$new[$key] = $value;');
    $this->codeStore->append('}');
    $this->codeStore->append('$tmp[] = $new;');
    $this->codeStore->append('}');
    $this->codeStore->append('');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function writeRoutineFunctionLobReturnData(): void
  {
    $this->throws(ResultException::class);

    $this->codeStore->append('if ($b===false) throw $this->dataLayerError(\'mysqli_stmt::fetch\');');
    $this->codeStore->append('if (sizeof($tmp)>1) throw new ResultException([0, 1], sizeof($tmp), $query);');
    $this->codeStore->append('');
    $this->codeStore->append('return ($tmp) ? $tmp[0] : null;');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
