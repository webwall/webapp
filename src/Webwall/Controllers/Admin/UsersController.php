<?php

namespace Webwall\Controllers\Admin;

use Webwall\Forms;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class UsersController extends BaseController implements ControllerProviderInterface {

  protected $template_path = "/admin/users/";

  protected $active_page = 'users';

  public function ls(Application $app) {
    $users = $app['manager.user']->get_all();
    return $this->render('list', array('users' => $users));
  }

  public function edit(Application $app) {
    $user = $app['manager.user']->get_id($id);

    $userForm = $app['form.factory']->create(new \Webwall\Forms\User());
    $userForm->setData($user);

    return $this->render('edit', array(
      'form' => $userForm->createView(),
      'action' => $app["url_generator"]->generate('admin.users.save'))
    );
  }

  public function save(Application $app) {
    $userForm = $app['form.factory']->create(new \Webwall\Forms\User());
    $userForm->handleRequest($app['request']);

    if ($userForm->isValid()) {
      $data = $userForm->getData();

      if(($user = $app['manager.user']->update($data, $id)) == true) {
        $app['session']->getFlashBag()->add('notice', 'Page updated.');
        return $app->redirect($app['url_generator']->generate('admin.users.ls'));
      }
    }

    $app['session']->getFlashBag()->add('error', 'Form contains errors');

    return $this->render('edit', array(
        'form' => $userForm->createView(),
        'action' => $app['url_generator']->generate('admin.users.save')
      ));
  }

  public function add(Application $app) {
    $pageForm = $app['form.factory']->create(new \Webwall\Forms\User());
    return $this->render('edit', array(
      'form' => $pageForm->createView(),
      'action' => $app["url_generator"]->generate('admin.users.create'))
    );

  }

  public function create(Application $app) {
    $pageForm = $app['form.factory']->create(new \Webwall\Forms\User());
    $pageForm->handleRequest($app['request']);

    if ($pageForm->isValid()) {
      $data = $pageForm->getData();
      if(($page = $app['manager.user']->create($data)) == true) {
        $app['session']->getFlashBag()->add('notice', 'User created.');
        return $app->redirect($app['url_generator']->generate('admin.users.ls'));
      }
    }

    $app['session']->getFlashBag()->add('error', 'Form contains errors');

    return $this->render('edit', array(
      'form' => $pageForm->createView(),
      'action' => $app["url_generator"]->generate('admin.users.create'))
    );
  }

  public function connect(Application $app) {
    parent::connect($app);

    $index = $app['controllers_factory'];
    $index->match('/', array($this, 'ls'))->bind('admin.users.ls');
    $index->get('/edit/{id}', array($this, 'edit'))->bind('admin.users.edit');
    $index->post('/edit/{id}', array($this, 'save'))->bind('admin.users.save');
    $index->get('/new', array($this, 'add'))->bind('admin.users.add');
    $index->post('/new', array($this, 'create'))->bind('admin.users.create');

    return $index;
  }


}
