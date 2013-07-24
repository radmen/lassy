<?php namespace Radmen\Lassy;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Filesystem\Filesystem;

class Lassy {

  protected $filters = array();

  /**
   * @var \Illuminate\Filesystem\Filesystem
   */
  protected $filesystem;

  protected $outputDir;

  /**
   * Enable saving generated site
   *
   * @var boolean
   */
  protected $enabled = true;

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

  /**
   * Enable output saving
   */
  public function enable() {
    $this->enabled = true;
  }

  /**
   * Disable output saving
   */
  public function disable() {
    $this->enabled = false;
  }

  public function addFilter($filter) {

    if(true === is_array($filter)) {

      foreach($filter as $callback) {
        $this->addFilter($callback);
      }

      return;
    }

    if(false === is_callable($filter)) {
      throw new \InvalidArgumentException('Filter needs to be a valid callback');
    }

    $this->filters[] = $filter;
  }

  /**
   * Check if response can be stored
   *
   * @param \Illuminate\Http\Request $request
   * @param \Illuminate\Http\Response $response
   * @return boolean if FALSE response won't be saved
   */
  protected function canSave(Request $request, Response $response) {

    foreach($this->filters as $callback) {

      if(false === $callback($request, $response)) {
        return false;
      }
    }

    return true;
  }

  /**
   * Get file path based on request pathinfo
   *
   * @param \Illuminate\Http\Request $request
   * @return string
   */
  public function getFilePath(Request $request) {
    $pathinfo = $request->getPathInfo();

    if('' == $this->filesystem->extension($pathinfo)) {
      $file = 'index.html';
      $dir = trim($pathinfo, '/');
    }
    else {
      $file = basename($pathinfo);
      $dir = trim(dirname($pathinfo), '/');
    }

    if(true === empty($dir)) {
      return "{$this->outputDir}/{$file}";
    }

    return "{$this->outputDir}/{$dir}/{$file}";
  }

  /**
   * Attempt to save response to HTML file
   *
   * @param \Illuminate\Http\Request $request
   * @param \Illuminate\Http\Response $response
   */
  public function save(Request $request, Response $response) {

    if(false === $this->enabled) {
      return;
    }

    if(false === $this->canSave($request, $response)) {
      return;
    }

    $fullpath = $this->getFilePath($request);
    $dir = dirname($fullpath);

    if(true === $this->filesystem->isFile($fullpath)) {
      throw new \RuntimeException('Bump. Static file exists.');
    }

    if(false === $this->filesystem->isDirectory($dir)) {
      $this->filesystem->makeDirectory($dir, 0777, true);
    }

    $this->filesystem->put($fullpath, $response->getContent());
  }

}
