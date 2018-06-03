<?php
  namespace Triton\Purchases;

  use Phalcon\Loader
      , Phalcon\Mvc\Dispatcher
      , Phalcon\Mvc\View
      , Phalcon\Mvc\ModuleDefinitionInterface
      , Phalcon\Mvc\Url
      , Phalcon\Db\Adapter\Pdo\Mysql
      ;

  class Module implements ModuleDefinitionInterface
  {
    /**
     * Register a specific autoloader for the module
     */
    public function registerAutoloaders() {
      $loader = new Loader();
      $loader->registerNamespaces(
        array(
          'Triton\Purchases\Controllers' => '../app/purchases/controllers/',
          'Triton\Purchases\Models'      => '../app/purchases/models/',
        )
      );
      $loader->register();
    }

    /**
     * Register specific services for the module
     */
    public function registerServices($di) {
      // Register the dispatcher setting a Namespace for controllers
      $di->set('dispatcher', function() {
        $dispatcher = new Dispatcher();
        $dispatcher->setDefaultNamespace('Triton\Purchases\Controllers');
        return $dispatcher;
      });
      //Setup the database service
      $di->set('db', function(){
        return new Mysql(array(
          "host" => "localhost",
          "username" => "root",
          "password" => "tri90juan10ton30galf",
          "dbname" => "triton"
        ));
      });
      //Setup the view component
      $di->set('view', function(){
          $view = new View();
          $view->setViewsDir('../app/purchases/views/');
          // Register volt as our template engine.
          $view->registerEngines(array(
            ".volt" => 'Phalcon\Mvc\View\Engine\Volt'
          ));
          return $view;
      });
      //Setup a base URI.
      $di->set('url', function(){
        $url = new Url();
        $url->setBaseUri('/triton/');
        return $url;
      });
    }
  }