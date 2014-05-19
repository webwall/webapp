<?php

namespace Webwall\Models;

use Silex\Application;
use Truss\Managers\MongoManager;

class UserManager extends MongoManager {

  protected $collection = 'users';

  protected $fields = array('email', 'password', 'firstname', 'lastname', 'companyname', 'website');

  function get_users() {
    // $this->coll->insert(array('email' => 'nikcub@test.com', 'company' => 'test company'));
    $users = $this->coll->find();
    // var_dump($users);
    // exit();
    return $users;
  }

  function get_id($id) {
    return $this->coll->findOne(array('_id' => new \MongoId($id)));
  }

  function get_email($email) {
    return $this->coll->findOne(array('email' => $email));
  }

  function add_user($data) {
    $insert_rec = array();
    // var_dump($data);
    foreach($this->fields as $model_field) {
      if(!in_array($model_field, $data) || isset($data[$model_field])) return false;
      $insert_rec[$model_field] = $data[$model_field];
    }
    return $this->coll->insert($insert_rec);
  }

  function email_exists($email) {
    return false;
  }
}
