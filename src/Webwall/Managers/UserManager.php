<?php

namespace Webwall\Managers;

use Silex\Application;

class UserManager extends DbalManager {

  protected $model_name = '\\Webwall\\Models\\User';

  protected $fields = array('firstname', 'surname', 'email', 'password', 'active', 'permissions');

  function get_users() {

    // $sql = "SELECT * FROM user";
    // $users = $this->db->fetchAssoc($sql);
    $q = $this->db->executeQuery("select * from user");
    return $q->fetchAll();
    return $users;
    return $this->for_template($users);
  }

  function get_id($id) {
    $sql = "SELECT * FROM user WHERE id = ?";
    $user = $this->db->fetchAssoc($sql, array((int) $id));
    return $user;
  }

  function get_email($email) {
    return $this->coll->findOne(array('email' => $email));
  }

  function add_user($data) {
    $_ins_array = array();
    foreach($data as $field => $value) {
      if(in_array($field, $this->fields)) {
        $_ins_array[$field] = $value;
      }
    }
    return (bool)$this->db->insert('user', $_ins_array);
    // $user = \Model::factory($this->model_name)->create();
    // foreach($data as $field => $value) {
    //   $user->set($field, $value);
    // }
    // $user->save();
    // return $user->id();
  }

  function email_exists($email) {
    return false;
  }
}
