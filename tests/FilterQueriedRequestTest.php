<?php

use Illuminate\Http\Request;
use Radmen\Lassy\Filter\QueriedRequest as Filter;

class FilterQueriedRequestTest extends PHPUnit_Framework_TestCase {

  public function testIfFalseWhenQueryInRequest() {
    $request = Request::create('https://www.google.com/?gws_rd=cr&q=query');
    $filter = new Filter;

    $this->assertFalse($filter->filter($request));
  }

  public function testIfTrueWhenEmptyQueryInRequest() {
    $request = Request::create('https://www.google.com');
    $filter = new Filter;

    $this->assertTrue($filter->filter($request));
  }

}
