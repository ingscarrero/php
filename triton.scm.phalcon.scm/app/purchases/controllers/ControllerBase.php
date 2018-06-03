<?php
  //namespace Triton\Purchases\Controllers;
  
  class ControllerBase extends \Phalcon\Mvc\Controller
  {
    protected function initialize(){
      // General parameters fot he view.
      $js_general_parameters = array(
        "base_uri" => $this->url->getBaseUri(),
      );
      $this->view->setVar("js_general_parameters", $js_general_parameters);
      //$this->view->setVar("hola", "amigos");
      $this->view->setTemplateAfter('main');
    }
    
    /**
     * This is the bootstrap for all AJAX calls. Route the call to the right
     * service and return the results as JSON.
     * 
     * @return \Phalcon\Http\Response
     */
    public function ajaxAction(){
      // Call the service in this class passing the parameters.
      $service = $_GET['service'] . "Ajax";
      $result = $this->$service($_POST);
      // Return the result.
      // This is a service, not a page.
      $this->view->disable();      
      //Create a response instance
      $response = new \Phalcon\Http\Response();
      //Set the content of the response
      $response->setStatusCode(200, "OK");
      //Set the content of the response
      $response->setContent(json_encode($result));
      //Return the response
      return $response;
    }
  }