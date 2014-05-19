<?php

namespace Truss\Managers;

use \Silex\Application;

class MongoManager{


  function __construct(Application $app){
    $this->app = $app;
    $this->conn = $this->app['mongo']['default'];
    $this->database = $app['config.database'];
    $db = $this->conn->selectDb($this->database);
    $this->coll = $db->selectCollection($this->collection);
  }


  function getDb() {
    $db = $this->connection->selectDB($this->database);
    return $db;
  }

  function getCollection() {
    $db = $this->getDb();
    $collection = $db->selectCollection($this->collection);
    return $collection;
  }

}
