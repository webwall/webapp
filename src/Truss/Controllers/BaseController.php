<?php

namespace Truss\Controllers;

class BaseController {

  function __construct($model=null) {
    if($model) {
      $this->model = $model;
    }
  }

  function not_found() {
    return 'not found';
  }
}
