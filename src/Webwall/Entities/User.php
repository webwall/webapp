<?php

namespace Webwall\Entities;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, \ArrayAccess {

  protected $id = null;
  protected $firstname = null;
  protected $surname = null;
  protected $email = null;
  protected $password = null;
  protected $roles = array();
  protected $active = null;
  protected $lastlogin = null;

  protected $fields = array('firstname', 'surname', 'email', 'password', 'roles', 'active', 'lastlogin');

  public function __construct($username, $password=null, $roles=null, $enabled=false, $usernotexpired=false, $credsnotexpired=false, $notlocked=false) {
    if(is_string($username)) {
      $this->email = $username;
      $this->password = $password;
      $this->roles = $roles;
      $this->active = $enabled;
    } else if(is_array($username)) {
      foreach($username as $key => $value){
        if(in_array($key, $this->fields) && property_exists($this, $key))
          $this->$key = $value;
      }

    }
  }

  function __get($name) {
    $method = "get".ucwords($name);
    if(method_exists($this,$method)):
      return $this->$method();
    elseif (property_exists($this, $name)):
      return $this->$name;
    endif;
  }

  function __set($name, $value) {
    $method = "set".ucwords($name);
    if(method_exists($this, $method)):
      return $this->$method($value);
    elseif (property_exists($this, $name)):
      $this->$name = $value;
    endif;
  }

  function offsetExists($offset){
    if(property_exists($this, $offset)):
      return true;
    else:
      return false;
    endif;
  }
  function offsetGet($offset){
    return $this->__get($offset);
  }
  function offsetSet($offset,$value){
    return $this->__set($offset,$value);
  }
  function offsetUnset($offset){
    if(property_exists($this, $offset)):
      unset($this->$offset);
    endif;
  }

  function __toString(){
    ob_start();
    var_dump($this);
    return ob_get_clean();
  }

  public function id() {
    return $this->id;
  }

  public function getRoles() {
    return $this->roles;
  }

  public function getUsername() {
    return $this->email;
  }

  public function eraseCredentials() {
    return false;
  }

  public function getPassword() {
    return $this->password;
  }

  public function getSalt() {
    return null;
  }

  public function toArray() {
    return array(
        'firstname' => $this->firstname,
        'surname' => $this->surname,
        'email' => $this->email,
        'password' => $this->password,
        'roles' => implode(', ', $this->roles),
        'active' => (int)$this->active
      );

  }
}
