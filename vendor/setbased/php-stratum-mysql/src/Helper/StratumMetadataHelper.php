<?php
declare(strict_types=1);

namespace SetBased\Stratum\MySql\Helper;

use SetBased\Exception\RuntimeException;
use SetBased\Stratum\Backend\StratumStyle;
use SetBased\Stratum\Common\Helper\Util;

/**
 * Class for storing the metadata of stored routines.
 */
class StratumMetadataHelper
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The path to the file were the metadata is stored.
   *
   * @var string
   */
  private string $filename;

  /**
   * The revision number of the format of the metadata.
   *
   * @var string
   */
  private string $revision;

  /**
   * The metadata of routines. Key is the routine name.
   *
   * @var array[]
   */
  private array $routines;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $filename The path to the file where the metadata is stored.
   * @param string $revision The revision number of the format of the metadata.
   *
   * @throws RuntimeException
   */
  public function __construct(string $filename, string $revision)
  {
    $this->filename = $filename;
    $this->revision = $revision;

    $this->readMetadata();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Removes the metadata of a routine.
   *
   * @param string $routineName The name of the routine.
   */
  public function delMetadata(string $routineName): void
  {
    unset($this->routines[$routineName]);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the metadata of all stored routines.
   *
   * @return array
   */
  public function getAllMetadata(): array
  {
    return $this->routines;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the metadata of a routine. If no metadata is available an empty array is returned.
   *
   * @param string $routineName The name of the routine.
   *
   * @return array
   */
  public function getMetadata(string $routineName): array
  {
    return $this->routines[$routineName] ?? [];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Removes the metadata of stored routines are not in a list of stored routine names.
   *
   * @param string[] $routineNames The list of stored routine names.
   */
  public function purge(array $routineNames): void
  {
    $this->routines = array_intersect_key($this->routines, array_flip($routineNames));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Stores the metadata of a stored routine.
   *
   * @param string $routineName The name of the routine.
   * @param array  $metadata    The metadata of the routine.
   */
  public function putMetadata(string $routineName, array $metadata): void
  {
    $this->routines[$routineName] = $metadata;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Writes the metadata of all stored routines to the metadata file.
   *
   * @param StratumStyle $io The output object.
   *
   * @throws RuntimeException
   */
  public function writeMetadata(StratumStyle $io): void
  {
    $data = ['revision' => $this->revision,
             'routines' => $this->routines];

    $json = json_encode($data, JSON_PRETTY_PRINT);
    if (json_last_error()!==JSON_ERROR_NONE)
    {
      throw new RuntimeException("Error of encoding to JSON: '%s'.", json_last_error_msg());
    }

    Util::writeTwoPhases($this->filename, $json, $io);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Reads the metadata from file.
   *
   * @throws RuntimeException
   */
  private function readMetadata(): void
  {
    if (file_exists($this->filename))
    {
      $data = json_decode(file_get_contents($this->filename), true);
      if (json_last_error()!==JSON_ERROR_NONE)
      {
        throw new RuntimeException("Error decoding JSON: '%s'.", json_last_error_msg());
      }
    }

    if (!is_array($data ?? null) || ($data['revision'] ?? null)!==$this->revision)
    {
      $data = null;
    }

    $this->routines = $data['routines'] ?? [];
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
