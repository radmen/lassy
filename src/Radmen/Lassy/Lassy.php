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

  protected function canSave(Request $request, Response $response) {

    foreach($this->filters as $callback) {

      if(false === $callback($request, $response)) {
        return false;
      }
    }

    return true;
  }

  public function save(Request $request, Response $response) {

    if(false === $this->enabled) {
      return;
    }

    if(false === $this->canSave($request, $response)) {
      return;
    }

    $pathinfo = $request->getPathInfo();

    if('' == $this->filesystem->extension($pathinfo)) {
      $file = 'index.html';
      $dir = $this->outputDir.'/'.trim($pathinfo, '/');
    }
    else {
      $file = basename($pathinfo);
      $dir = $this->outputDir.'/'.trim(dirname($pathinfo), '/');
    }

    $dir = rtrim($dir, '/');

    if(true === $this->filesystem->isFile($dir.'/'.$file)) {
      return;
    }

    if(false === $this->filesystem->isDirectory($dir)) {
      $this->filesystem->makeDirectory($dir, 0777, true);
    }

    $this->filesystem->put($dir.'/'.$file, $response->getContent());
  }

}
