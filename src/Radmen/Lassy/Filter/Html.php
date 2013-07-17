<?php namespace Radmen\Lassy\Filter;

use Illuminate\Http\Response;

class Html {

  /**
   * Allow Lassy to save only valid html responses
   *
   * @param \Illuminate\Http\Response $response
   * @return boolean
   */
  public function filter(Response $response) {

    if(200 !== $response->getStatusCode()) {
      return false;
    }

    if(false === strpos($response->headers->get('Content-Type'), 'text/html')) {
      return false;
    }

    return true;
  }

}
