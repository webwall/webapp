<?php

namespace Webwall\Managers;

use Silex\Application;
use Truss\Managers\DbalManager;

class PageManager extends DbalManager {

  protected $timestamp_match = '/^(((\d{4})(-)(0[13578]|10|12)(-)(0[1-9]|[12][0-9]|3[01]))|((\d{4})(-)(0[469]|1‌​1)(-)([0][1-9]|[12][0-9]|30))|((\d{4})(-)(02)(-)(0[1-9]|1[0-9]|2[0-8]))|(([02468]‌​[048]00)(-)(02)(-)(29))|(([13579][26]00)(-)(02)(-)(29))|(([0-9][0-9][0][48])(-)(0‌​2)(-)(29))|(([0-9][0-9][2468][048])(-)(02)(-)(29))|(([0-9][0-9][13579][26])(-)(02‌​)(-)(29)))(\s([0-1][0-9]|2[0-4]):([0-5][0-9]):([0-5][0-9]))$/';

  function get_published() {
    $q = $this->db->executeQuery("select * from page where status=1 order by created desc");
    return $q->fetchAll();
  }

  function get_all() {
    $q = $this->db->executeQuery("select * from page");
    return $q->fetchAll();
  }

  function get_stub($stub) {
    $q = $this->db->executeQuery("select * from page where stub=?", array($stub));
    return $q->fetch();
  }

  function update($data, $id) {
    return $this->db->update('page', $this->transformDataIn($data), array('id' => $id));
  }

  function get_id($id) {
    $sql = "SELECT * FROM page WHERE id = ?";
    $user = $this->transformDataOut($this->db->fetchAssoc($sql, array((int) $id)));
    return $user;
  }

  function get_email($email) {
    return $this->coll->findOne(array('email' => $email));
  }

  function create(array $data) {
    return $this->db->insert('page', $this->transformDataIn($data));
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

}
