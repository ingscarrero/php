<?php
  //namespace Triton\Purchases\Controllers;
  
  class IndexController extends ControllerBase
  //class IndexController extends Phalcon\Mvc\Controller
  {
    public function indexAction(){
      // Pass the general parameters to the view.
      $this->view->setVar('page_id', 'purchases-index');
      $this->view->setVar('title', 'Purchases');
    }
  }