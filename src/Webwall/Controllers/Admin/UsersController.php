<?php

namespace Webwall\Controllers\Admin;

use Webwall\Forms;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class UsersController extends BaseController implements ControllerProviderInterface {

  protected $template_path = "/admin/users/";

  public function index(Application $app) {
    $ret = array();
    $ret = array_merge($ret, $this->highlite_active('dashboard'));
    return $app['twig']->render('index', $ret);
  }

  public function userlist(Application $app) {
    $users = $app['manager.user']->get_users();
    return $this->render('list', array('users' => $users));
  }

  public function usernew(Application $app) {
    $signupForm = $app['form.factory']->create(new \Webwall\Forms\Signup(), array("attr" => array("class"=>"test")));
    return $this->render('edit', array(
      'form'=>$signupForm->createView(),
      'action'=>$app["url_generator"]->generate('admin.users.new'))
    );
  }

  public function usercreate(Application $app) {
    $registrationForm = $app['form.factory']->create(new \Webwall\Forms\Signup(), array("attr" => array("class"=>"test")));
    $registrationForm->handleRequest($app['request']);

    if ($registrationForm->isValid()) {
      $data = $registrationForm->getData();
      $user = $app['paris']->getModel('User')->create();

      $user->firstname = $data->title;

      $user->save();

      if($um->email_exists($data['email']) == true) {
        $registrationForm->addError(new FormError('email already exists'));
      }
      if($um->add_user($data) === true) {
        $app['session']->getFlashBag()->add('notice', 'User created.');
        return $app->redirect($app['url_generator']->generate('homepage'));
      } else {
        $app['session']->getFlashBag()->add('error', 'Could not add user (database error)');
      }
    }

    $app['session']->getFlashBag()->add('error', 'Form contains errors');
    return $app['twig']->render('signup_form.html', array("registrationForm" => $registrationForm->createView(), 'action' => '/user/signup'));
  }

  public function edit(Application $app, $id) {

    $user = $this->model->get_id($id);
    if(!$user) {
      return $this->not_found();
    }
    $signupForm = $app['form.factory']->create(new \Webwall\Forms\Signup(), $user, array("attr" => array("class"=>"test")));
    $signupForm->setData($user);
    var_dump($user);
    return $this->render('edit', array(
      'user'=>$user,
      'form'=>$signupForm->createView(),
      'action'=>$app["url_generator"]->generate('admin.users.edit', array('id'=>$id)))
    );
  }

  public function connect(Application $app) {
    $this->app = $app;
    $index = $app['controllers_factory'];
    $index->match('/', array($this, 'userlist'))->bind('admin.users.index');
    $index->match('/edit/{id}', array($this, 'edit'))->bind('admin.users.edit');
    $index->match('/list', array($this, 'userlist'))->bind('admin.users.list');
    $index->get('/new', array($this, 'usernew'))->bind('admin.users.new');
    $index->post('/new', array($this, 'usercreate'))->bind('admin.users.create');
    // $index->match('/login', array($this, 'login'))->bind('user.login');
    // $index->get('/logout', array($this, 'logout'))->bind('user.logout');
    // $index->get('/signup', array($this, 'signup'))->bind('user.signup');
    // $index->post('/signup', array($this, 'dosignup'))->bind('user.dosignup');
    return $index;
  }


}
