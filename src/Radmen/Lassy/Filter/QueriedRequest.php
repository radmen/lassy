<?php namespace Radmen\Lassy\Filter;

use Illuminate\Http\Request;

class QueriedRequest {

  /**
   * Disable Lassy when request has query
   *
   * @param \Illuminate\Http\Request $request
   * @return boolean
   */
  public function filter(Request $request) {
    $query = $request->query();

    return empty($query);
  }

}
