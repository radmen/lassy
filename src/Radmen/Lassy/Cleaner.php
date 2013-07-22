<?php namespace Radmen\Lassy;

use Illuminate\Filesystem\Filesystem;

class Cleaner {

  protected $outputDir;

  /**
   * @var \Illuminate\Filesystem\Filesystem
   */
  protected $filesystem;

  /**
   * @param string $outputDir
   * @param \Illuminate\Filesystem\Filesystem $filesystem
   */
  public function __construct($outputDir, Filesystem $filesystem) {

    if(false === is_string($outputDir)) {
      throw new \InvalidArgumentException('outputDir must be valid string');
    }

    $this->outputDir = $outputDir;
    $this->filesystem = $filesystem;
  }

  public function clear() {
    $this->filesystem->cleanDirectory($this->outputDir);
  }

}
