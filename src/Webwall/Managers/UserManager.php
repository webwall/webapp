<?php

namespace Webwall\Managers;

use Silex\Application;

use Webwall\Entities\User;

class UserManager extends DbalManager  {

  protected $table_name = 'user';

  public function get_email($email) {
    $sql = "SELECT * FROM " . $this->table_name ." WHERE email = ?";
    $user = $this->db->fetchAssoc($sql, array($email));
    if(is_array($user)) {
      // @TODO migrate this to USer entity
      return $this->transformDataOut($user);
    }
    return false;
  }

  public function email_exists($email) {
    return false;
  }

  public function save(User $user) {
    $u = $user->toArray();
    if($user->id()) {
      return $this->db->update($this->table_name, $u, $user->id());
    } else {
      return $this->db->insert($this->table_name, $u);
    }
  }
  // public function create($data) {
  //   var_dump($data);
  //   return $this->db->insert($this->table_name, $this->transformDataIn($data));
  // }

}
