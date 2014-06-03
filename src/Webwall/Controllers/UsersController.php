<?php

namespace Webwall\Controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

use Symfony\Component\Form\FormError;

use Webwall\Forms;
use Truss\Controllers\BaseController;

class UsersController extends BaseController implements ControllerProviderInterface {

  public function dosignup(Application $app) {
    $registrationForm = $app['form.factory']->create(new \Webwall\Forms\Signup(), array("attr" => array("class"=>"test")));
    $registrationForm->handleRequest($app['request']);

    if ($registrationForm->isValid()) {
      $data = $registrationForm->getData();
      $um = $app['um'];
      if($um->email_exists($data['email']) == true) {
        $registrationForm->addError(new FormError('email already exists'));
      }
      if($um->add_user($data) == true) {
        $app['session']->getFlashBag()->add('notice', 'User created.');
        return $app->redirect($app['url_generator']->generate('homepage'));
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

  public function login(Application $app) {
    return '';
  }

  public function logout(Application $app) {
    return '';
  }

  public function userlist(Application $app) {
    $um = $app['user_manager'];
    $users = $um->get_users();
    return $app['twig']->render('list.html', array('users'=>$users));
  }

  public function connect(Application $app) {
    $index = $app['controllers_factory'];
    $index->match('/list', array($this, 'userlist'))->bind('user.list');
    $index->match('/login', array($this, 'login'))->bind('user.login');
    $index->get('/logout', array($this, 'logout'))->bind('user.logout');
    $index->get('/signup', array($this, 'signup'))->bind('user.signup');
    $index->post('/signup', array($this, 'dosignup'))->bind('user.dosignup');
    return $index;
  }
}
