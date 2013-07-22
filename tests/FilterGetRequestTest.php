<?php

use Mockery as m;
use Radmen\Lassy\Filter\GetRequest as Filter;

class FilterGetRequestTest extends PHPUnit_Framework_TestCase {

  public function tearDown() {
    m::close();
  }

  public function testIfTrueWhenRequestIsGet() {
    $filter = new Filter();
    $request = m::mock('Illuminate\Http\Request');
    $request->shouldReceive('getMethod')->once()->andReturn('GET');

    $this->assertTrue($filter->filter($request));
  }

  public function testIfFalseWhenRequestIsDifferentThanGet() {
    $filter = new Filter();
    $request = m::mock('Illuminate\Http\Request');
    $request->shouldReceive('getMethod')->once()->andReturn('POST');

    $this->assertFalse($filter->filter($request));
  }

}
