<?php

namespace Webwall\Controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class PagesController extends BaseController implements ControllerProviderInterface {

  public function index(Application $app) {
    return $this->render('index');
  }

  public function page(Application $app, $slug) {
    $this->app = $app;
    $pm = $app['manager.page'];
    if (($pv = $pm->get_stub($slug)) === false) {
      return $this->render('404', array());
    }
    return $this->render('page', $pv);
  }

  public function connect(Application $app) {
    $this->app = $app;
    $index = $app['controllers_factory'];
    $index->get('/', array($this, 'index'))->bind('home');
    $index->get('/{slug}', array($this, 'page'))->bind('page');
    return $index;
  }
}
