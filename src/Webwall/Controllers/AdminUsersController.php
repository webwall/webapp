<?php

namespace Webwall\Controllers;

use Webwall\Forms;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

use Truss\Controllers\BaseController;

class AdminUsersController extends BaseController implements ControllerProviderInterface {

  public function highlite_active($active_page) {
    $pages = array(
        'dashboard_active' => '',
        'posts_active' => '',
        'pages_active' => '',
        'users_active' => '',
        'settings_active' => '',
      );
    $pages[$active_page . '_active'] = 'active';
    return $pages;
  }

  public function index(Application $app) {
    $ret = array();
    $ret = array_merge($ret, $this->highlite_active('dashboard'));
    return $app['twig']->render('admin\users\index.html', $ret);
  }

  public function userlist(Application $app) {
    $users = $this->model->get_users();
    return $this->render('list', array('users' => $users));
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
    // $index->match('/login', array($this, 'login'))->bind('user.login');
    // $index->get('/logout', array($this, 'logout'))->bind('user.logout');
    // $index->get('/signup', array($this, 'signup'))->bind('user.signup');
    // $index->post('/signup', array($this, 'dosignup'))->bind('user.dosignup');
    return $index;
  }

  public function render($template, $vars, $active='users') {
    $vars = array_merge($vars, $this->highlite_active($active));
    return $this->app['twig']->render('admin\users\\' . $template . '.html', $vars);

  }
}
