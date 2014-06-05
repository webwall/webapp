<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Monolog\Logger;
use Webwall\Controllers;
use Webwall\Extensions\BCryptEncoder;

define('APP_ROOT', __DIR__);

$app = new Silex\Application();
$app['debug'] = (WEBWALL_ENV == 'dev') ? true : false;

// Logging
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => WEBWALL_DIR_VAR .'/logs/development.log',
    'monolog.level' => Logger::DEBUG
));

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

// Security
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
  // 'security.providers' => array(
  //     'user_db' => array(
  //       'entity' => array(
  //         'class' => 'Acme\UserBundle\Entity\User',
  //         'property' => 'username',
  //         ),
  //       )
  //   ),
  'security.firewalls'=>array(
    'login' => array(
        'pattern' => '^/user/login$',
        'anonymous' => true,
    ),
    'admin' => array(
      'pattern' => '^/admin/',
      "anonymous" => array(),
      'form' => array(
        'login_path' => "/user/login",
        'check_path' => "/admin/login_check",
        // "default_target_path" => "/user/profile",
        // "always_use_default_target_path" => true,
        'username_parameter' => 'login[username]',
        'password_parameter' => 'login[password]',
        "csrf_parameter" => "login[_token]",
        // "failure_path" => "/user/login",
        ),
      'logout' => array(
        'logout_path' => "/user/logout",
        // "target" => '/',
        // "invalidate_session" => true,
        ),
      'users' => $app->share(function() use ($app) {
        return new Webwall\Providers\UserProvider($app['manager.user']);
      })
      )
    ),
  // 'security.access_rules' => array(
  //   array('^/admin', 'ROLE_ADMIN'),
  //   // array('^/admin/option','ROLE_ADMIN'),
  //   ),
  // 'security.role_hierarchy'=> array(
  //   'ROLE_ADMIN' => array('ROLE_EDITOR'),
  //   // "ROLE_EDITOR" => array('ROLE_WRITER'),
  //   // "ROLE_WRITER" => array('ROLE_USER'),
  //   "ROLE_USER" => array("ROLE_ADMIN"),
  //   ),
  )
);

$app['security.encoder.digest'] = $app->share(function ($app) {
  return new BCryptEncoder();
});

$app->register(new Silex\Provider\TranslationServiceProvider(), array("locale_fallback" => "en"));
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());


$app['manager.user'] = $app->share(function(Silex\Application $app) {
  return new Webwall\Managers\UserManager($app);
});

$app['manager.page'] = $app->share(function(Silex\Application $app) {
  return new Webwall\Managers\PageManager($app);
});

$app['manager.post'] = $app->share(function(Silex\Application $app) {
  return new Webwall\Managers\PostManager($app);
});

// $admin = $app['controllers_factory'];

// seperate this into a file ..  VVVVVVV

$app->mount("/admin", new Webwall\Controllers\Admin\IndexController());
$app->mount("/admin/users", new Webwall\Controllers\Admin\UsersController());
$app->mount("/admin/pages", new Webwall\Controllers\Admin\PagesController());
$app->mount("/admin/posts", new Webwall\Controllers\Admin\PostsController());
$app->mount("/user", new Webwall\Controllers\UsersController());
$app->mount("/", new Webwall\Controllers\PagesController());

$app->run();

