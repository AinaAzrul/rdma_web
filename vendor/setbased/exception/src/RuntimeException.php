<?php
declare(strict_types=1);

namespace SetBased\Exception;

/**
 * Class for runtime exceptions.
 */
class RuntimeException extends \RuntimeException implements NamedException
{
  //--------------------------------------------------------------------------------------------------------------------
  use FormattedException;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getName(): string
  {
    return 'Error';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------