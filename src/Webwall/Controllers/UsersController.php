<?php

namespace Webwall\Controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\User as SymfonyUser;

use Webwall\Forms;
use Webwall\Entities\User;

class UsersController extends BaseController implements ControllerProviderInterface {

  public function dosignup(Application $app) {
    $registrationForm = $app['form.factory']->create(new \Webwall\Forms\Signup());
    $registrationForm->handleRequest($app['request']);

    if ($registrationForm->isValid()) {
      $data = $registrationForm->getData();
      $um = $app['manager.user'];
      if($um->email_exists($data['email']) == true) {
        $registrationForm->addError(new FormError('email already exists'));
      }
      $data['password'] = self::encodePassword($data['email'], $data['password'], $app);
      $user = new User($data);
      if($um->save($user) == true) {
        $app['session']->getFlashBag()->add('notice', 'User created.');
        return $app->redirect($app['url_generator']->generate('home'));
      } else {
        $app['session']->getFlashBag()->add('error', 'Could not add user (database error)');
        // $registrationForm->addError(new FormError('Could not add user (database error)'));
      }
    }

    $app['session']->getFlashBag()->add('error', 'Form contains errors');
    // $registrationForm->addError(new FormError('Form contains errors'));
    return $this->render('signup_form',  array("registrationForm" => $registrationForm->createView(), 'action' => '/user/signup'));
    // return $app['twig']->render('signup_form.html', array("registrationForm" => $registrationForm->createView(), 'action' => '/user/signup'));
  }

  public function signup(Application $app) {
    $registrationForm = $app['form.factory']->create(new \Webwall\Forms\Signup());
    return $app['twig']->render('signup_form.html', array("registrationForm" => $registrationForm->createView(), 'action' => '/user/signup'));
  }

  public function login(Application $app, Request $request) {
    $loginForm = $app['form.factory']->create(new \Webwall\Forms\Login());
    $form_error = $app['security.last_error']($request);
    if ($form_error != null):
      $loginForm->addError(new FormError($form_error));
      $app['session']->getFlashBag()->add("error", "Wrong credentials");
    endif;
    $last_username = $app['session']->get('_security.last_username');
    return $this->render('login', array(
        'loginForm'     => $loginForm->createView(),
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
      ));
  }

  public function dologin(Application $app) {

  }
  public function logout(Application $app) {
    return '';
  }

  public function userlist(Application $app) {
    $um = $app['user_manager'];
    $users = $um->get_users();
    return $app['twig']->render('list.html', array('users'=>$users));
  }

  static function encodePassword($username, $rawpass, Application $app){
    $user = new SymfonyUser($username, $rawpass);
    $encoder = $app['security.encoder_factory']->getEncoder($user);
    $encodedPassword = $encoder->encodePassword($rawpass, $user->getSalt());
    return $encodedPassword;
  }

  public function connect(Application $app) {
    $index = $app['controllers_factory'];
    $index->match('/list', array($this, 'userlist'))->bind('user.list');
    $index->match('/login', array($this, 'login'))->bind('user.login');
    // $index->post('/login', array($this, 'dologin'))->bind('user.dologin');
    $index->get('/logout', array($this, 'logout'))->bind('user.logout');
    $index->get('/signup', array($this, 'signup'))->bind('user.signup');
    $index->post('/signup', array($this, 'dosignup'))->bind('user.dosignup');
    parent::connect($app);
    return $index;
  }
}
