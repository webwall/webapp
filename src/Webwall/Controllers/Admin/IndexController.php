<?php

namespace Webwall\Controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class AdminController implements ControllerProviderInterface {

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
    return $app['twig']->render('admin/index.html', $ret);
  }

  public function connect(Application $app) {
    $index = $app['controllers_factory'];
    $index->match('/', array($this, 'index'))->bind('admin.index');
    // $index->match('/list', array($this, 'userlist'))->bind('admin.list');
    // $index->match('/login', array($this, 'login'))->bind('user.login');
    // $index->get('/logout', array($this, 'logout'))->bind('user.logout');
    // $index->get('/signup', array($this, 'signup'))->bind('user.signup');
    // $index->post('/signup', array($this, 'dosignup'))->bind('user.dosignup');
    return $index;
  }
}
