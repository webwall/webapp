<?php

namespace Webwall\Controllers;

use \Silex\Application;
use \Silex\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\JsonResponse;

abstract class BaseController implements ControllerProviderInterface {

  protected $app;

  protected $template_path = null;

  public function connect(Application $app) {
    $this->app = $app;
  }

  public function highlite_active($active_page=null) {
    if(!isset($this->page_sections) || !is_array($this->page_sections)) {
      return array();
    }

    $section_names = array();

    foreach($this->page_sections as $ps) {
      $section_names[$ps . '_active'] = '';
    }

    if($active_page)
      $pages[$active_page . '_active'] = 'active';

    return $section_names;
  }


  protected function template_append_messages($vars) {

    return $vars;
  }

  public function render($template, array $vars = array(), $active=null) {
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

    return $this->app['twig']->render($_template_path, $vars);

  }

  public function render_json($vars) {
    return new JsonResponse($vars);
  }
}
