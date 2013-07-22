<?php

use Mockery as m;
use Radmen\Lassy\Cleaner;

class CleanerTest extends PHPUnit_Framework_TestCase {

  public function tearDown() {
    m::close();
  }

  public function testIfCleanerThrowsExceptionWhenOutputDirIsNotString() {
    $this->setExpectedException('InvalidArgumentException');
    new Cleaner(null, m::mock('Illuminate\Filesystem\Filesystem'));
  }

  public function testIfClearCleansDirectory() {
    $fs = m::mock('Illuminate\Filesystem\Filesystem');
    $fs->shouldReceive('cleanDirectory')->once()->with($dir = '_static');

    $cleaner = new Cleaner($dir, $fs);
    $cleaner->clear();
  }

}
