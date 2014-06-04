<?php

namespace Webwall\Managers;

use Silex\Application;

class UserManager extends DbalManager {

  protected $table_name = 'user';

  function get_email($email) {
    return $this->coll->findOne(array('email' => $email));
  }

  function email_exists($email) {
    return false;
  }
}
