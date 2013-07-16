<?php

use Mockery as m;
use Radmen\Lassy\Lassy;

class LassyTest extends PHPUnit_Framework_TestCase {
  
  public function tearDown() {
    m::close();
  }

  public function testSaveWhenLassyIsDisabled() {
    $request = m::mock('Illuminate\Http\Request');
    $request->shouldReceive('getPathInfo')->never();

    $response = m::mock('Illuminate\Http\Response');
    $response->shouldReceive('getContent')->never();

    $lassy = new Lassy('', m::mock('Illuminate\Filesystem\Filesystem'));
    $lassy->disable();
    $lassy->save($request, $response);
  }

  public function testIfCanSaveMethodCallsFilters() {
    $passed = false;
    $filter = function(Illuminate\Http\Request $request) use (& $passed) {
      $passed = true;

      return false;
    };

    $request = m::mock('Illuminate\Http\Request');
    $request->shouldReceive('getPathInfo')->never();
    $lassy = new Lassy('', m::mock('Illuminate\Filesystem\Filesystem'));
    $lassy->addFilter($filter);

    $lassy->save($request, m::mock('Illuminate\Http\Response'));
    $this->assertTrue($passed);
  }

  public function testSaveProcessWhenPathEndsWithFileName() {
    $response = m::mock('Illuminate\Http\Response');
    $response->shouldReceive('getContent')->once()->andReturn($content = 'trolo');
    $request = Illuminate\Http\Request::create('/blog/posts/test.html');
    $fs = m::mock('Illuminate\Filesystem\Filesystem');
    $fs->shouldReceive('extension')->once()->with('/blog/posts/test.html')->andReturn('html');
    $fs->shouldReceive('isFile')->once()->with('_static/blog/posts/test.html')->andReturn(false);
    $fs->shouldReceive('isDirectory')->once()->with('_static/blog/posts')->andReturn(false);
    $fs->shouldReceive('makeDirectory')->once()->with('_static/blog/posts', 0777, true);
    $fs->shouldReceive('put')->once()->with('_static/blog/posts/test.html', $content);

    $lassy = new Lassy('_static', $fs);
    $lassy->save($request, $response);
  }

  public function testSaveProcessWhenPathEndsWithNoFileName() {
    $response = m::mock('Illuminate\Http\Response');
    $response->shouldReceive('getContent')->once()->andReturn($content = 'trolo');
    $request = Illuminate\Http\Request::create('/blog/posts/test/');
    $fs = m::mock('Illuminate\Filesystem\Filesystem');
    $fs->shouldReceive('extension')->once()->with('/blog/posts/test/')->andReturn('');
    $fs->shouldReceive('isFile')->once()->with('_static/blog/posts/test/index.html')->andReturn(false);
    $fs->shouldReceive('isDirectory')->once()->with('_static/blog/posts/test')->andReturn(false);
    $fs->shouldReceive('makeDirectory')->once()->with('_static/blog/posts/test', 0777, true);
    $fs->shouldReceive('put')->once()->with('_static/blog/posts/test/index.html', $content);

    $lassy = new Lassy('_static', $fs);
    $lassy->save($request, $response);
  }

  public function testSaveProcessForMainPage() {
    $response = m::mock('Illuminate\Http\Response');
    $response->shouldReceive('getContent')->once()->andReturn($content = 'trolo');
    $request = Illuminate\Http\Request::create('/');
    $fs = m::mock('Illuminate\Filesystem\Filesystem');
    $fs->shouldReceive('extension')->once()->with('/')->andReturn('');
    $fs->shouldReceive('isFile')->once()->with('_static/index.html')->andReturn(false);
    $fs->shouldReceive('isDirectory')->once()->with('_static')->andReturn(false);
    $fs->shouldReceive('makeDirectory')->once()->with('_static', 0777, true);
    $fs->shouldReceive('put')->once()->with('_static/index.html', $content);

    $lassy = new Lassy('_static', $fs);
    $lassy->save($request, $response);
  }

  public function testSaveProcessWhenFileExists() {
    $response = m::mock('Illuminate\Http\Response');
    $response->shouldReceive('getContent')->never();
    $request = Illuminate\Http\Request::create('/blog/posts/test.html');
    $fs = m::mock('Illuminate\Filesystem\Filesystem');
    $fs->shouldReceive('extension')->once()->with('/blog/posts/test.html')->andReturn('html');
    $fs->shouldReceive('isFile')->once()->with('_static/blog/posts/test.html')->andReturn(true);
    $fs->shouldReceive('isDirectory')->never();
    $fs->shouldReceive('makeDirectory')->never();
    $fs->shouldReceive('put')->never();

    $lassy = new Lassy('_static', $fs);
    $lassy->save($request, $response);
  }

  public function testSaveProcessWhenDirExists() {
    $response = m::mock('Illuminate\Http\Response');
    $response->shouldReceive('getContent')->once()->andReturn($content = 'trolo');
    $request = Illuminate\Http\Request::create('/blog/posts/test.html');
    $fs = m::mock('Illuminate\Filesystem\Filesystem');
    $fs->shouldReceive('extension')->once()->with('/blog/posts/test.html')->andReturn('html');
    $fs->shouldReceive('isFile')->once()->with('_static/blog/posts/test.html')->andReturn(false);
    $fs->shouldReceive('isDirectory')->once()->with('_static/blog/posts')->andReturn(true);
    $fs->shouldReceive('makeDirectory')->never();
    $fs->shouldReceive('put')->once()->with('_static/blog/posts/test.html', $content);

    $lassy = new Lassy('_static', $fs);
    $lassy->save($request, $response);
  }

}
