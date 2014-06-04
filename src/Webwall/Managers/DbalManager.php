<?php

namespace Webwall\Managers;

use Silex\Application;

class DbalManager {

  protected $app;
  protected $conn;
  protected $model;

  function __construct(Application $app){
    $this->app = $app;
    $this->db = $this->app['db'];
    // $this->model = $this->conn->getModel($this->model);
    // var_dump($this->model);
  }

  // function create_new() {
  //   $_cls_name = $this->model_name;
  //   return new $_cls_name();
  // }

  // function get_model_name() {

  // }

  // function for_template($records) {
  //   $_output = array();
  //   foreach($records as $r) {
  //     $u = array(
  //         'id' => $r->id,
  //         'firstname' => $r->firstname,
  //         'surname' => $r->surname,
  //         'email' => $r->email
  //       );
  //     $_output[] = $u;
  //   }
  //   return $_output;
  // }
}
