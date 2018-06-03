<?php
  namespace Triton\Purchases\Controllers;
  
  use Triton\Purchases\Models\PurchaseOrder;
  use Triton\Purchases\Models\ShipmentTerm;
  use Triton\Purchases\Models\DeliveryType;
  use Triton\Purchases\Models\PaymentTerm;
  use Triton\Purchases\Models\FreightForwarder;
  use Triton\Purchases\Models\WiringInstruction;
  use Triton\Purchases\Models\Companies;
  use Phalcon\Paginator\Adapter\QueryBuilder;
  use Phalcon\Paginator\Adapter\Model;
  
  class SupplierController extends ControllerBase{
    
    /**
     * Action executed when editing a Supplier.
     * 
     */
    public function listAction(){
      // Current PO to show.
      $currentSupplier = $this->request->getQuery('supplier', 'int');
      // Pass the general parameters to the view.
      $this->view->setVar('title', 'Supplier #' . $currentSupplier);
      $this->view->setVar('subtitle', 'dat dat dat');
      // @todo::: Define controls.
      $this->view->setVar('show_submit', TRUE);
      $this->view->setVar('submit_text', 'Submit');
      $this->view->setVar('show_cancel', TRUE);
      $this->view->setVar('cancel_text', "Cancel");
      $this->view->setVar('main_form_id', '');
      $this->view->setVar('exit_to', $this->url->getBaseUri() . 'po/search');
    }
    
    /**
     * Action executed when editing a Supplier.
     * 
     */
    public function editAction(){
      // Current PO to show.
      $currentSupplier = $this->request->getQuery('supplier', 'int');
      // Pass the general parameters to the view.
      $this->view->setVar('title', 'Supplier #' . $currentSupplier);
      $this->view->setVar('subtitle', 'dat dat dat');
      // @todo::: Define controls.
      $this->view->setVar('show_submit', TRUE);
      $this->view->setVar('submit_text', 'Submit');
      $this->view->setVar('show_cancel', TRUE);
      $this->view->setVar('cancel_text', "Cancel");
      $this->view->setVar('main_form_id', '');
      $this->view->setVar('exit_to', $this->url->getBaseUri() . 'po/search');
    }

  }