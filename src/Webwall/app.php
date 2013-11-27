<?php

// use Silex\Application;
// use Silex\Provider\TwigExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();

if(WEBWALL_ENV == 'dev') {
  $app['debug'] = true;
} else {
  $app['debug'] = false;
}

$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => WEBWALL_DIR_THEME,
  'twig.class_path' => __DIR__.'/../vendor/twig/twig/lib',
  'twig.options' => array('cache' => WEBWALL_DIR_VAR.'/cache'),
));

$app->get('/', function (Request $request) use ($app) {
  $msg = $request->get('test');
  return $app['twig']->render('index.html', array(
      'msg' => $msg,
    ));

  // return new Response('You sent:' . $msg, 200);
});

$app->run();

