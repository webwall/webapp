<?php

namespace Webwall\Controllers\Admin;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class IndexController extends BaseController implements ControllerProviderInterface {

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
