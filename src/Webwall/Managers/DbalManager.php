<?php

namespace Webwall\Managers;

use Silex\Application;

class DbalManager {

  protected $app;
  protected $conn;
  protected $model;

  protected $timestamp_match = '/^(((\d{4})(-)(0[13578]|10|12)(-)(0[1-9]|[12][0-9]|3[01]))|((\d{4})(-)(0[469]|1‌​1)(-)([0][1-9]|[12][0-9]|30))|((\d{4})(-)(02)(-)(0[1-9]|1[0-9]|2[0-8]))|(([02468]‌​[048]00)(-)(02)(-)(29))|(([13579][26]00)(-)(02)(-)(29))|(([0-9][0-9][0][48])(-)(0‌​2)(-)(29))|(([0-9][0-9][2468][048])(-)(02)(-)(29))|(([0-9][0-9][13579][26])(-)(02‌​)(-)(29)))(\s([0-1][0-9]|2[0-4]):([0-5][0-9]):([0-5][0-9]))$/';

  function __construct(Application $app){
    $this->app = $app;
    $this->db = $this->app['db'];
    // $this->model = $this->conn->getModel($this->model);
    // var_dump($this->model);
  }

  function get_all() {
    $q = $this->db->executeQuery("select * from " . $this->table_name);
    return $q->fetchAll();
  }

  function update($data, $id) {
    return $this->db->update($this->table_name, $this->transformDataIn($data), array('id' => $id));
  }

  function get_id($id) {
    $sql = "SELECT * FROM " . $this->table_name ." WHERE id = ?";
    $user = $this->transformDataOut($this->db->fetchAssoc($sql, array((int) $id)));
    return $user;
  }

  function create(array $data) {
    return $this->db->insert($this->table_name, $this->transformDataIn($data));
  }

  public function transformDataOut($data) {
    $returnData = array();
    foreach($data as $field => $value) {
      if(preg_match($this->timestamp_match, $value)) {
        $v = new \DateTime($value);
      } else {
        $v = $value;
      }
      $returnData[$field] = $v;
    }
    return $returnData;
  }

  public function transformDataIn($data) {
    $returnData = array();
    foreach($data as $field => $value) {
      switch(gettype($value)) {
        case 'object':
          if($value instanceof \DateTime) {
            $v = date_format($value, "Y-m-d H:i:s");
          }
          break;
        default:
          $v = $value;
      }
      $returnData[$field] = $v;
    }
    return $returnData;
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
