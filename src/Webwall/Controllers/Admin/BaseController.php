<?php

namespace Webwall\Controllers\Admin;

use \Truss\Controllers\BaseController as TrussBaseController;

class BaseController extends TrussBaseController {
  protected $page_sections = array('dashboard', 'posts', 'pages', 'users', 'settings');
}