<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Radmen\Lassy\Filter;

return array(
  
  /*
  |--------------------------------------------
  | Output dir
  |--------------------------------------------
  |
  | Save generated static files in this directory
  |
  */

  'output_dir' => public_path().'/_static',

  /*
  |--------------------------------------------
  | Filters
  |--------------------------------------------
  |
  | If one of filters returns FALSE
  | Lassy will be disabled for the request
  |
  */

  'filters' => array(
    
    function(Request $request) {
      return with(new Filter\AjaxRequest)->filter($request);
    },

    function(Request $request) {
      return with(new Filter\GetRequest)->filter($request);
    },

    function(Request $request, Response $response) {
      return with(new Filter\HtmlResponse)->filter($response);
    },

    function(Request $request) {
      return with(new Filter\QueriedRequest)->filter($request);
    },

  ),

);
