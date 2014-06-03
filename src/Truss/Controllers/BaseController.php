<?php

namespace Truss\Controllers;

class BaseController {

  protected $template_path = null;

  function __construct(\Silex\Application $app) {
    $this->app = $app;
  }

  protected function highlite_active($active_page) {
    return array();
  }

  public function not_found() {
    return 'not found';
  }

  protected function template_append_messages($vars) {
    // var_dump($vars);
    // $messages = $this->app['session']->getFlashBag()->get("error");
    // $vars['error'] = $messages;
    // var_dump($vars['error']);
    // var_dump($vars);
    return $vars;
  }

  public function render($template, $vars, $active='users') {
    $vars = array_merge($vars, $this->highlite_active($active));

    $_template_path = '';
    if(!empty($this->template_path)) {
      $_template_path = $this->template_path;
    }
    $_template_path .= $template;
    if(substr($template, (strlen($template) - 5), 5) != '.html') {
      $_template_path .= '.html';
    }

    $vars = $this->template_append_messages($vars);
    // $vars = array_merge($vars, array('error' => $this->app['session']->getFlashBag()->get('error')));
    // print '<pre>';
    // var_dump($vars['error']);
    return $this->app['twig']->render($_template_path, $vars);

  }
}
