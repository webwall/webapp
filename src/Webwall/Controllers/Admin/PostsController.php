<?php

namespace Webwall\Controllers\Admin;

// use \Webwall\Controllers\Scaffold;


class PostsController extends Scaffold {
  protected $name = "post";
  protected $template_path = "/admin/posts/";
  protected $form_name = "\\Webwall\\Forms\\Post";
  protected $manager_name = "manager.post";
  protected $active_page = "posts";
  protected $route_space = "admin.posts";

}
