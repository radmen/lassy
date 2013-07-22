<?php namespace Radmen\Lassy\Filter;

use Illuminate\Http\Request;

class GetRequest {

  /**
   * Disable Lassy when request method is non-GET
   *
   * @param \Illuminate\Http\Request
   * @return boolean
   */
  public function filter(Request $request) {
    return 'GET' === $request->getMethod();
  }

}
