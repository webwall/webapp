<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Mongo\Silex\Provider\MongoServiceProvider;
use Webwall\Controllers;
use Webwall\Models\UserManager;

define('APP_ROOT', __DIR__);

$app = new Silex\Application();

if(WEBWALL_ENV == 'dev') {
  $app['debug'] = true;
} else {
  $app['debug'] = false;
}

// Twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => APP_ROOT . '/views',
  'twig.class_path' => APP_ROOT . '/../vendor/twig/twig/lib',
  'twig.options' => array('cache' => WEBWALL_DIR_VAR.'/cache'),
  "twig.form.templates"=>array('form_div_layout.html.twig',"forms/form_div_layout.html"),
));
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
  // $twig->addFilter('timesince', )
  return $twig;
}));

// Database
$app->register(new MongoServiceProvider, array(
  'mongo.connections' => array(
    'default' => array(
      'server' => 'mongodb://127.0.0.1:27017',
      'options' => array('connect' => true, 'db' => 'webwall')
      )
    )
));
$app['config.database'] = 'webwall';


$app['user_manager'] = $app->share(function(Silex\Application $app) {
  return new Webwall\Models\UserManager($app);
});

$app->register(new Silex\Provider\TranslationServiceProvider(), array("locale_fallback" => "en"));
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->mount("/", new Webwall\Controllers\PagesController());
$app->mount("/admin", new Webwall\Controllers\AdminController());
$app->mount("/admin/users", new Webwall\Controllers\AdminUsersController($app['user_manager']));
$app->mount("/user", new Webwall\Controllers\UsersController($app['user_manager']));

$app->run();

