<?php

use Mockery as m;
use Radmen\Lassy\Filter\AjaxRequest as Filter;

class FilterAjaxRequestTest extends PHPUnit_Framework_TestCase {

  public function tearDown() {
    m::close();
  }

  public function testIfFalseWhenAjaxRequest() {
    $filter = new Filter();
    $request = m::mock('Illuminate\Http\Request');
    $request->shouldReceive('ajax')->once()->andReturn(true);

    $this->assertFalse($filter->filter($request));
  }

  public function testIfTrueWhenNonAjaxRequest() {
    $filter = new Filter();
    $request = m::mock('Illuminate\Http\Request');
    $request->shouldReceive('ajax')->once()->andReturn(false);

    $this->assertTrue($filter->filter($request));
  }

}
