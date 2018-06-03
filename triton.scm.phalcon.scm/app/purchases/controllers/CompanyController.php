<?php
  namespace Triton\Purchases\Controllers;
  
  use Triton\Purchases\Models\PurchaseOrder;
  use Triton\Purchases\Models\ShipmentTerm;
  use Triton\Purchases\Models\DeliveryType;
  use Triton\Purchases\Models\PaymentTerm;
  use Triton\Purchases\Models\FreightForwarder;
  use Triton\Purchases\Models\WiringInstruction;
  use Triton\Purchases\Models\Companies;
  use Triton\Purchases\Models\Accounts;
  use Phalcon\Paginator\Adapter\QueryBuilder;
  use Phalcon\Paginator\Adapter\Model;
  
  class CompanyController extends ControllerBase{
    
    /**
     * @var string The namespace of the models used in the controller.
     */
    private $modelNamespace = 'Triton\Purchases\Models';
    
    /**
     * Action executed when listing suppliers.
     * 
     */
    public function listAction(){
      // Type of company
      $companyType = $this->request->getQuery('type', 'string');
      // Pass the general parameters to the view.
      $this->view->setVar('title', ucfirst($companyType) . 's Results');
      $this->view->setVar('subtitle', '');
      $this->view->setVar('company_type', $companyType);
      // @todo::: Define controls.
      $this->view->setVar('show_submit', TRUE);
      $this->view->setVar('submit_text', 'Add New');
      $this->view->setVar('show_cancel', TRUE);
      $this->view->setVar('cancel_text', "New Search");
      $this->view->setVar('main_form_id', '');
      $this->view->setVar('route_to', $this->url->getBaseUri() . 'company/new?type=' . $companyType);
      $this->view->setVar('exit_to', $this->url->getBaseUri() . 'company/search?type=' . $companyType);
      // Current page to show
      $currentPage = $this->request->getQuery('page', 'int');
      // The data set to paginate
      $builder = $this->modelsManager->createBuilder();
      $builder->columns('c.company_id, c.company_name, c.phones, c.address, c.address2, c.city, c.state, c.zip_code, a.account_type_id');
      $builder->addFrom($this->modelNamespace . '\Companies', 'c');
      $builder->join($this->modelNamespace . '\Accounts', 'c.account_id = a.account_id', 'a');
      // Set the type of company.
      if ($companyType == 'supplier') {
        $builder->andWhere('a.account_type_id = 3'); 
      }
      if ($companyType == 'freight') {
        $builder->andWhere('a.account_type_id = 12'); 
      }
      // Get the search criteria.
      $sName = $this->request->getPost('name', 'string');
      $sCity = $this->request->getPost('city', 'string');
      $sState = $this->request->getPost('state', 'string');
      // Construct the query.
      if ( $sName ) {
        $builder->andWhere("c.company_name LIKE '%$sName%'"); 
      }
      if ( $sCity ) {
        $builder->andWhere("c.city = '$sCity'"); 
      }
      if ( $sState ) {
        $builder->andWhere("c.state = '$sState'"); 
      }
      $paginator = new QueryBuilder(array(
        "builder" => $builder,
        "limit"=> 30,
        "page" => $currentPage,
      ));      
      // Get the paginated results
      $page = $paginator->getPaginate();
      // Pass it to the view.
      $this->view->setVar('page', $page);
    }
    
    /**
     * Action executed when showing the search form.
     * 
     */
    public function searchAction(){
      // Type of company
      $companyType = $this->request->getQuery('type', 'string');
      // Pass the general parameters to the view.
      $this->view->setVar('company_type', $companyType);
      $this->view->setVar('title', ucfirst($companyType) . 's');
      $this->view->setVar('subtitle', '');
      // @todo::: Define controls.
      $this->view->setVar('show_submit', FALSE);
      //$this->view->setVar('submit_text', 'Submit');
      $this->view->setVar('show_cancel', FALSE);
      //$this->view->setVar('cancel_text', "Cancel");
      $this->view->setVar('main_form_id', 'search_company_form');
      $this->view->setVar('exit_to', $this->url->getBaseUri() . 'company/list?type=' . $companyType);
    }
    
    /**
     * New company form.
     * 
     */
    public function newAction(){
      // Type of company
      $companyType = $this->request->getQuery('type', 'string');
      // Pass the general parameters to the view.
      $this->view->setVar('company_type', $companyType);
      $this->view->setVar('title', 'New ' . ucfirst($companyType));
      $this->view->setVar('subtitle', '');
      // Define controls.
      $this->view->setVar('show_submit', TRUE);
      $this->view->setVar('submit_text', 'Save');
      $this->view->setVar('show_cancel', TRUE);
      $this->view->setVar('cancel_text', "Cancel");
      $this->view->setVar('main_form_id', 'new-company-form');
      $this->view->setVar('exit_to', $this->url->getBaseUri() . 'company/list?type=' . $companyType);
      // Form Lists.
      // $this->view->setVar('shipment_terms_list', ShipmentTerm::find());
      // Form predefined fields.
      $this->view->setVar('now', date('Y-m-d'));
    }
    
    /**
     * Action executed when submit the new PO form. Saves the purchase order.
     * 
     */
    public function saveAction(){
      // Type of company
      $companyType = $this->request->getQuery('type', 'string');
      $this->view->setVar('company_type', $companyType);
      // Transform the post parameters in DB fields.
      $post_array = $this->request->getPost();
      // Fix the date to have right value and avoid fraud.
      $post_array['created'] = date('Y-m-d h:i:s');
      // Other calculated values.
      $post_array['created_by'] = 'admin';
      $post_array['country'] = 'USA';
      // Instantiate model object and Store 
      if ( $post_array['company_id'] ) {
        // update
        $company = Companies::findFirst(array('company_id = ' . $post_array['company_id']));
        $success = $company->update($post_array, array('company_name', 'address', 'address2', 'phones', 'faxes', 'city', 'state', 'zip_code', 'webpage', 'email'));
      }
      else {
        // @todo::: Create account with account type = $companyType.
        if ($companyType == 'supplier') {
          $company_type_id = 3;
        }
        elseif ($companyType == 'freight') {
          $company_type_id = 12;
        }
        if ( $company_type_id ) {
          $account = new Accounts();
          $success = $account->save(
            array(
              'account_name' => $post_array['company_name']
              ,'account_type_id' => $company_type_id
              ,'account_owner_id' => 'admin'
              ,'account_status_id' => '1'
              ,'created' => $post_array['created']
            )
          );
          if ( $success ) {
            // Create the company.
            $company = new Companies();
            // @todo::: fix this
            //$post_array['account_id'] = $account->account_id;
            $post_array['account_id'] = $account->account_id;
            $success = $company->save($post_array);
          }
        }
      }
      
      // Results.
      if ($success) {
        // Forward flow to the edit action.
        $this->dispatcher->forward(array(
            "action" => "edit"
            ,"params" => array($company->company_id)
        ));
      }
      // Error.
      else {
        echo "Sorry, the following problems were generated: ";
        foreach ($po->getMessages() as $message) {
          echo $message->getMessage(), "<br/>";
        }
        // @todo::: Show error page. Now showing plain message.
        $this->view->disable();      
      }
    }
    
    /**
     * Edit company form.
     * 
     */
    public function editAction(){
      // The companyid can come as a get parameter or as a parameter in 
      // dispatcher.
      $cid = $this->request->getQuery('cid', 'int');
      //
      if (!$cid) {
        $params = $this->dispatcher->getParams();
        $cid = $params[0];
      }
      // Get the info.
      $company = Companies::findFirst(array("company_id = " . $cid));
      // @todo::: Get the company type.
      $companyType = 'supplier';
      if ( $company->account_id ) {
        $account = Accounts::findFirst(array("account_id = " . $company->account_id));
        if ( $account->account_type_id == 3 ) {
          $companyType = 'supplier';
        }
        elseif ( $account->account_type_id == 12 ) {
          $companyType = 'freight';
        }
      }
      $this->view->setVar('company_type', $companyType);
      // Pass the general parameters to the view.
      $this->view->setVar('title', ucfirst($company->company_name));
      $this->view->setVar('subtitle', 'Created: ' . $company->created);
      // Define controls.
      $this->view->setVar('show_submit', TRUE);
      $this->view->setVar('submit_text', 'Save');
      $this->view->setVar('show_cancel', TRUE);
      $this->view->setVar('cancel_text', "Cancel");
      $this->view->setVar('main_form_id', 'edit-company-form');
      $this->view->setVar('exit_to', $this->url->getBaseUri() . 'company/list?type=' . $companyType);
      // Send the company to the view.
      $this->view->setVar('company', $company);
      // Form Lists.
      // $this->view->setVar('shipment_terms_list', ShipmentTerm::find());
      // Form predefined fields.
      $this->view->setVar('now', date('Y-m-d'));
    }

  }