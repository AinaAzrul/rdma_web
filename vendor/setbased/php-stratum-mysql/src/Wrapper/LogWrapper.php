<?php
declare(strict_types=1);

namespace SetBased\Stratum\MySql\Wrapper;

use SetBased\Stratum\MySql\Exception\MySqlDataLayerException;

/**
 * Class for generating a wrapper method for a stored procedure 'selecting' rows for logging.
 */
class LogWrapper extends Wrapper
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getDocBlockReturnType(): string
  {
    return 'int';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getReturnTypeDeclaration(): string
  {
    return ': int';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function writeResultHandler(): void
  {
    $this->throws(MySqlDataLayerException::class);

    $routineArgs = $this->getRoutineArgs();
    $this->codeStore->append('return $this->executeLog(\'call '.$this->routine['routine_name'].'('.$routineArgs.')\');');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function writeRoutineFunctionLobFetchData(): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function writeRoutineFunctionLobReturnData(): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
