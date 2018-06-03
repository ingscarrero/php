<?php
  namespace Triton\Purchases\Controllers;
  
  use Triton\Purchases\Models\Users;

  class SignupController extends ControllerBase
  {
    public function indexAction()
    {
      // Pass the $postId parameter to the view
      $this->view->setVar("title", 'Please Signup');
    }
    public function registerAction()
    {
      $user = new Users();
      //Store and check for errors
      $success = $user->save($this->request->getPost(), array('name', 'email'));
      if ($success) {
        echo "Thanks for registering!";
      } else {
        echo "Sorry, the following problems were generated: ";
        foreach ($user->getMessages() as $message) {
          echo $message->getMessage(), "<br/>";
        }
      }
      $this->view->disable();      
    }
    
    /**
     * Ajax service.
     * 
     * @param type $parameters
     * @return type
     */
    public function testAjax($parameters){
      // Parameters.
      $name = $parameters['name'];
      $last_name = $parameters['last_name'];
      //return $response;
      return $name . ' ' . $last_name;
    }
  }