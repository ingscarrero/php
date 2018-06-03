<?php
/*
  use 
      Phalcon\Mvc\Application
      ,Phalcon\DI\FactoryDefault
      ,Phalcon\Mvc\Router
  ;
  use \Phalcon\Logger\Adapter\File as FileAdapter;
  try {
    //Create a DI
    $di = new FactoryDefault();
    //Specify routes for modules
    $di->set('router', function () {
      $router = new Router();
      $router->setDefaultModule("purchases");
      $router->add('/:controller/:action', array(
        'module' => 'purchases',
        'controller' => 1,
        'action' => 2,
      ));
      $router->add("/index", array(
        'module'     => 'purchases',
        'controller' => 'index',
        'action'     => 'index',
      ));
      return $router;
    });
    // Set up the logger.
    $logger = new FileAdapter("logs/debug_old.log");
    $di->set('logger', $logger);
    $application = new Application($di);
    $application->logger->log('1');
    //$application->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    // Register the installed modules
    $application->registerModules(
      array(
        'purchases' => array(
          'className' => 'Triton\Purchases\Module',
          'path'      => '../app/purchases/Module.php',
        ),
      )
    );
    $application->logger->log('2');
    //Handle the request
    echo 'h';
    $application->logger->log('3');
    echo $application->handle()->getContent();
    $application->logger->log('4');
    echo 'hj';
  } 
  catch(\Phalcon\Exception $e) {
    echo "PhalconException::: ", $e->getMessage();
  }
 * 
 */

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\Application;
use Phalcon\DI\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

try {
  require "../app/purchases/controllers/ControllerBase.php";

    // Register an autoloader
    $loader = new Loader();
    $loader->registerDirs(array(
        '../app/purchases/controllers/',
        '../app/purchases/models/'
    ))->register();

    // Create a DI
    $di = new FactoryDefault();
    
    // Setup the view component
    $di->set('view', function(){
        $view = new View();
        $view->setViewsDir('../app/purchases/views/');
        // Register volt as our template engine.
        $view->registerEngines(array(".volt" => 'Phalcon\Mvc\View\Engine\Volt'));
        return $view;
    });

    //Setup the database service
    $di->set('db', function(){
      return new DbAdapter(array(
        "host" => "localhost",
        "username" => "tritonapp",
        "password" => "yu56fqr30*bj$3",
        "dbname" => "triton"
      ));
    });
    
    // Setup a base URI so that all generated URIs include the "tutorial" folder
    $di->set('url', function(){
        $url = new UrlProvider();
        $url->setBaseUri('/triton/');
        return $url;
    });

    // Handle the request
    $application = new Application($di);

    echo $application->handle()->getContent();

} catch(\Exception $e) {
     echo "PhalconException: ", $e->getMessage();
}