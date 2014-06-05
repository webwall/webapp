<?php

namespace Webwall\Providers;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use Symfony\Component\Security\Core\User\User as SymfonyUser;

use Webwall\Managers\UserManager;
use Webwall\Entity\User;

class UserProvider implements UserProviderInterface {
  private $um;

  public function __construct(UserManager $um) {
    $this->um = $um;
  }


  // security methods
  public function loadUserByUsername($username) {
    print 'called UserProvider with ' . $username;
    $user = $this->um->get_email($username);
    if($user == false) {
      throw new \Exception(sprintf("user %s not found", $username));
    }
    return new User($user['email'], $user['password'], explode(',', $user['roles']), true, true, true, true);
    return new User($user);
  }

  public function refreshUser(UserInterface $user) {
    return $this->loadUserByUsername($user);
  }

  public function supportsClass($class) {
    return $class === 'Symfony\Component\Security\Core\User\User';
  }

}
