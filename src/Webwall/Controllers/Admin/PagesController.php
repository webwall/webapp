<?php

namespace Webwall\Controllers\Admin;

use Webwall\Forms;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class PagesController extends BaseController implements ControllerProviderInterface {

  protected $template_path = "/admin/pages/";

  protected $active_page = 'pages';

  public function ls(Application $app) {
    $pages = $app['manager.page']->get_all();
    return $this->render('list', array('pages' => $pages));
  }

  public function edit(Application $app, $id=null) {
    $page = $app['manager.page']->get_id($id);

    $pageForm = $app['form.factory']->create(new \Webwall\Forms\Page(), $page);
    $pageForm->setData($page);

    return $this->render('edit', array(
      'form' => $pageForm->createView(),
      'action' => $app["url_generator"]->generate('admin.pages.edit', array('id'=>$id)))
    );
  }

  public function save(Application $app, $id=null) {
    $pageForm = $app['form.factory']->create(new \Webwall\Forms\Page());
    $pageForm->handleRequest($app['request']);

    if ($pageForm->isValid()) {
      $data = $pageForm->getData();

      if(($page = $app['manager.page']->update($data, $id)) == true) {
        $app['session']->getFlashBag()->add('notice', 'Page updated.');
        return $app->redirect($app['url_generator']->generate('admin.pages.ls'));
      }
    }

    $app['session']->getFlashBag()->add('error', 'Form contains errors');

    return $this->render('edit', array(
      'form' => $pageForm->createView(),
      'action' => $app["url_generator"]->generate('admin.pages.create'))
    );
  }

  public function add(Application $app) {
    $pageForm = $app['form.factory']->create(new \Webwall\Forms\Page());
    return $this->render('edit', array(
      'form' => $pageForm->createView(),
      'action' => $app["url_generator"]->generate('admin.pages.create'))
    );
  }

  public function create(Application $app) {
    $pageForm = $app['form.factory']->create(new \Webwall\Forms\Page());
    $pageForm->handleRequest($app['request']);

    if ($pageForm->isValid()) {
      $data = $pageForm->getData();
      if(($page = $app['manager.page']->create($data)) == true) {
        $app['session']->getFlashBag()->add('notice', 'Page created.');
        return $app->redirect($app['url_generator']->generate('admin.pages.ls'));
      }
    }

    $app['session']->getFlashBag()->add('error', 'Form contains errors');

    return $this->render('edit', array(
      'form' => $pageForm->createView(),
      'action' => $app["url_generator"]->generate('admin.pages.create'))
    );
  }

  public function connect(Application $app) {
    parent::connect($app);

    $index = $app['controllers_factory'];
    $index->match('/', array($this, 'ls'))->bind('admin.pages.ls');
    $index->get('/edit/{id}', array($this, 'edit'))->bind('admin.pages.edit');
    $index->post('/edit/{id}', array($this, 'save'))->bind('admin.pages.save');
    $index->get('/new', array($this, 'add'))->bind('admin.pages.add');
    $index->post('/new', array($this, 'create'))->bind('admin.pages.create');
    // $index->match('/login', array($this, 'login'))->bind('user.login');
    // $index->get('/logout', array($this, 'logout'))->bind('user.logout');
    // $index->get('/signup', array($this, 'signup'))->bind('user.signup');
    // $index->post('/signup', array($this, 'dosignup'))->bind('user.dosignup');

    return $index;
  }


}
