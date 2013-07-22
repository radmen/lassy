<?php namespace Radmen\Lassy\Filter;

use Illuminate\Http\Request;

class AjaxRequest {

  /**
   * Disable Lassy on ajax requests
   *
   * @param \Illuminate\Http\Request
   * @return boolean
   */
  public function filter(Request $request) {

    if(true === $request->ajax()) {
      return false;
    }

    return true;
  }

}
