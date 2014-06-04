<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Mongo\Silex\Provider\MongoServiceProvider;

use Webwall\Controllers;
use Webwall\Models\UserManager;

use Truss\Provider\ParisServiceProvider;

define('APP_ROOT', __DIR__);

$app = new Silex\Application();
$app['debug'] = (WEBWALL_ENV == 'dev') ? true : false;


// Dbal
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
  'db.options' => array(
    'driver'   => 'pdo_mysql',
    'host'   => '127.0.0.1',
    'dbname'   => 'webwall',
    'user'   => 'root',
    'password'   => '',
    'charset'   => 'utf8',
  ),
));

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

$app->register(new Silex\Provider\TranslationServiceProvider(), array("locale_fallback" => "en"));
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());


$app['um'] = $app->share(function(Silex\Application $app) {
  return new Webwall\Managers\UserManager($app);
});

$app['page_manager'] = $app->share(function(Silex\Application $app) {
  return new Webwall\Managers\PageManager($app);
});

// $admin = $app['controllers_factory'];

// seperate this into a file ..  VVVVVVV

$app->mount("/admin", new Webwall\Controllers\Admin\IndexController());
$app->mount("/admin/users", new Webwall\Controllers\Admin\UsersController());
$app->mount("/admin/pages", new Webwall\Controllers\Admin\PagesController());
$app->mount("/user", new Webwall\Controllers\UsersController());
$app->mount("/", new Webwall\Controllers\PagesController());

$app->run();

