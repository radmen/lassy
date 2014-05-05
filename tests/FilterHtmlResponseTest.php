<?php

use Mockery as m;
use Radmen\Lassy\Filter\HtmlResponse as Filter;

class FilterHtmlTest extends PHPUnit_Framework_TestCase {

  public function setUp() {
    m::getConfiguration()->allowMockingNonExistentMethods(false);
  }

  public function tearDown() {
    m::close();
  }

  public function testIfFalseWhenResponseIsNot200() {
    $response = m::mock('Illuminate\Http\Response');
    $response->shouldReceive('getStatusCode')->once()->andReturn(404);

    $filter = new Filter();
    $this->assertFalse($filter->filter($response));
  }

  public function testIfFalseWhenResponseIsNotHtml() {
    $response = new Illuminate\Http\Response('', 200, array(
      'Content-Type' => 'applications/json',
    ));

    $filter = new Filter();
    $this->assertFalse($filter->filter($response));
  }

  public function testIfTrue() {
    $response = new Illuminate\Http\Response('', 200, array(
      'Content-Type' => 'text/html; charset=utf8',
    ));

    $filter = new Filter();
    $this->assertTrue($filter->filter($response));
  }

}
