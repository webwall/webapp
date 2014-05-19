<?php

namespace Webwall\Controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class PagesController implements ControllerProviderInterface {

  public function index(Application $app) {
    return $app['twig']->render('index.html');
  }

  public function product(Application $app) {
    return $app['twig']->render('product.html');
  }

  public function about(Application $app) {
    return $app['twig']->render('about.html');
  }

  public function contact(Application $app) {
    return $app['twig']->render('contact.html');
  }

  public function connect(Application $app) {
    $index = $app['controllers_factory'];
    $index->get('/', 'Webwall\Controllers\PagesController::index')->bind('homepage');
    $index->get('/product', 'Webwall\Controllers\PagesController::product')->bind('product');
    $index->get('/about', 'Webwall\Controllers\PagesController::about')->bind('about');
    $index->get('/contact', 'Webwall\Controllers\PagesController::contact')->bind('contact');
    return $index;
  }
}
