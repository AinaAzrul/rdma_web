<?php
declare(strict_types=1);

namespace SetBased\Stratum\MySql\Helper;

use SetBased\Stratum\Common\Helper\SourceFinderHelper;

/**
 * Helper class for converting tags in the source of stored routines to the new specification.
 */
class ConvertTagsHelper
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The key in $source where the DocBlock ends.
   *
   * @var int|null
   */
  private ?int $docBlockEndKey;

  /**
   * The source as array of sources.
   *
   * @var string[]
   */
  private array $source = [];

  /**
   * The old style tags.
   *
   * @var array
   */
  private array $tags;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts tags in sources of stored routines to the new specification.
   *
   * @param string $pattern The pattern for find the sources of stored routines.
   */
  public function convert(string $pattern)
  {
    $finder    = new SourceFinderHelper(getcwd());
    $filenames = $finder->findSources($pattern);

    foreach ($filenames as $filename)
    {
      try
      {
        $this->convertFile($filename);
      }
      catch (\Throwable $exception)
      {
        echo $exception->getMessage();
        echo $exception->getTraceAsString();
        echo PHP_EOL;
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts the new tags to the source.
   */
  private function InsertNewTags()
  {
    if (isset($this->tags['param']))
    {
      $this->insertLine(sprintf(' * @paramAddendum %s', $this->tags['param']['value']));
    }

    if (isset($this->tags['type']))
    {
      $this->insertLine(sprintf(' * @type %s', $this->tags['type']['value']));
    }

    if (isset($this->tags['return']))
    {
      $this->insertLine(sprintf(' * @return %s', $this->tags['return']['value']));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Removes the old tags for the code.
   */
  private function removeOldTags()
  {
    foreach ($this->tags as $tag)
    {
      unset($this->source[$tag['key']]);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Saves the source code.
   *
   * @param string $filename The name of the source file.
   */
  private function saveSource(string $filename): void
  {
    $code = trim(implode(PHP_EOL, $this->source)).PHP_EOL;

    $tmpFilename = $filename.'.tmp';
    file_put_contents($tmpFilename, $code);
    rename($tmpFilename, $filename);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts the tags of source of a stored routine.
   *
   * @param string $filename The name of the source file.
   */
  private function convertFile(string $filename): void
  {
    echo sprintf("Converting %s\n", $filename);

    $this->readSource($filename);
    $this->findDocBlock();
    $this->findOldStyleTags();
    $this->removeOldTags();
    $this->InsertNewTags();
    $this->saveSource($filename);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Finds the DocBlock in the sources.
   */
  private function findDocBlock()
  {
    $start                = null;
    $this->docBlockEndKey = null;

    foreach ($this->source as $key => $line)
    {
      if ($start===null)
      {
        $n = preg_match('/^\s*\/\*\*\s*$/', $line);
        if ($n===1)
        {
          $start = $key;
        }
      }
      elseif ($this->docBlockEndKey===null)
      {
        $n = preg_match('/^\s*\*\/\s*$/', $line);
        if ($n===1)
        {
          $this->docBlockEndKey = $key;
        }
      }
      else
      {
        break;
      }
    }

    if ($start===null && $this->docBlockEndKey===null)
    {
      echo "  No DocBlock found. Inserting empty DocBloc.\n";

      array_unshift($this->source, ' */');
      array_unshift($this->source, ' *');
      array_unshift($this->source, '/**');

      $this->docBlockEndKey = 2;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Finds the old style tags.
   */
  private function findOldStyleTags(): void
  {
    $this->tags = [];

    foreach ($this->source as $key => $line)
    {
      $n = preg_match('/^\s*--\s*(?<tag>type|return|param)\s*:\s*(?<value>.*)$/', $line, $matches);
      if ($n===1)
      {
        $this->tags[$matches['tag']] = ['key'   => $key,
                                        'value' => trim($matches['value'])];
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Insert a line at the end of the DocBlock.
   *
   * @param string $line The line of code.
   */
  private function insertLine(string $line): void
  {
    $n = preg_match('/^\s*\*\s*$/', $this->source[$this->docBlockEndKey - 1]);
    if ($n===0)
    {
      array_splice($this->source, $this->docBlockEndKey, 0, ' *');
      $this->docBlockEndKey += 1;
    }

    array_splice($this->source, $this->docBlockEndKey, 0, $line);
    $this->docBlockEndKey += 1;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Reads the source file.
   *
   * @param string $filename The name of the source file.
   */
  private function readSource(string $filename): void
  {
    $source = file_get_contents($filename);

    $this->source = explode(PHP_EOL, $source);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
