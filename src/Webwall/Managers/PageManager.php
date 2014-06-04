<?php

namespace Webwall\Managers;

use Silex\Application;

class PageManager extends DbalManager {

  protected $table_name = 'page';

  function get_published() {
    $q = $this->db->executeQuery("select * from page where status=1 order by created desc");
    return $q->fetchAll();
  }

  function get_email($email) {
    return $this->coll->findOne(array('email' => $email));
  }

  function get_stub($stub) {
    $q = $this->db->executeQuery("select * from page where stub=?", array($stub));
    return $q->fetch();
  }





}
