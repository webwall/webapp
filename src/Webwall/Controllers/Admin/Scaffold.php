<?php

namespace Webwall\Controllers\Admin;

use Webwall\Forms;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class Scaffold extends BaseController implements ControllerProviderInterface {


  public function ls(Application $app) {
    $pages = $app[$this->manager_name]->get_all();
    return $this->render('list', array($this->name . 's' => $pages));
  }

  public function edit(Application $app, $id=null) {
    $page = $app[$this->manager_name]->get_id($id);

    $pageForm = $app['form.factory']->create(new $this->form_name(), $page);
    $pageForm->setData($page);

    return $this->render('edit', array(
      'form' => $pageForm->createView(),
      'action' => $app["url_generator"]->generate($this->route_space . '.edit', array('id'=>$id)))
    );
  }

  public function save(Application $app, $id=null) {
    $pageForm = $app['form.factory']->create(new $this->form_name());
    $pageForm->handleRequest($app['request']);

    if ($pageForm->isValid()) {
      $data = $pageForm->getData();

      if(($page = $app[$this->manager_name]->update($data, $id)) == true) {
        $app['session']->getFlashBag()->add('notice', 'record updated.');
        return $app->redirect($app['url_generator']->generate($this->route_space . '.ls'));
      }
    }

    $app['session']->getFlashBag()->add('error', 'Form contains errors');

    return $this->render('edit', array(
      'form' => $pageForm->createView(),
      'action' => $app["url_generator"]->generate($this->route_space . '.create'))
    );
  }

  public function add(Application $app) {
    $pageForm = $app['form.factory']->create(new $this->form_name());
    return $this->render('edit', array(
      'form' => $pageForm->createView(),
      'action' => $app["url_generator"]->generate($this->route_space . '.create'))
    );
  }

  public function create(Application $app) {
    $pageForm = $app['form.factory']->create(new $this->form_name());
    $pageForm->handleRequest($app['request']);

    if ($pageForm->isValid()) {
      $data = $pageForm->getData();
      if(($page = $app[$this->manager_name]->create($data)) == true) {
        $app['session']->getFlashBag()->add('notice', 'record created.');
        return $app->redirect($app['url_generator']->generate($this->route_space . '.ls'));
      }
    }

    $app['session']->getFlashBag()->add('error', 'Form contains errors');

    return $this->render('edit', array(
      'form' => $pageForm->createView(),
      'action' => $app["url_generator"]->generate($this->route_space . '.create'))
    );
  }

  public function connect(Application $app) {
    parent::connect($app);

    $index = $app['controllers_factory'];
    $index->match('/', array($this, 'ls'))->bind($this->route_space . '.ls');
    $index->get('/edit/{id}', array($this, 'edit'))->bind($this->route_space . '.edit');
    $index->post('/edit/{id}', array($this, 'save'))->bind($this->route_space . '.save');
    $index->get('/new', array($this, 'add'))->bind($this->route_space . '.add');
    $index->post('/new', array($this, 'create'))->bind($this->route_space . '.create');
    // $index->match('/login', array($this, 'login'))->bind('user.login');
    // $index->get('/logout', array($this, 'logout'))->bind('user.logout');
    // $index->get('/signup', array($this, 'signup'))->bind('user.signup');
    // $index->post('/signup', array($this, 'dosignup'))->bind('user.dosignup');

    return $index;
  }


}
