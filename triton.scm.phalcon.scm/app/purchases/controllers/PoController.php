<?php
//namespace Triton\Purchases\Controllers;
/*
use Triton\Purchases\Models\PurchaseOrder;
use Triton\Purchases\Models\ShipmentTerm;
use Triton\Purchases\Models\DeliveryType;
use Triton\Purchases\Models\PaymentTerm;
use Triton\Purchases\Models\FreightForwarder;
use Triton\Purchases\Models\WiringInstruction;
use Triton\Purchases\Models\Companies;
use Triton\Purchases\Models\Branch;
use Triton\Purchases\Models\PoProduct;
use Triton\Purchases\Models\Units;
use Triton\Purchases\Models\PoInvoice;
use Triton\Purchases\Models\Inventory;
*/


use Phalcon\Paginator\Adapter\QueryBuilder;
use Phalcon\Paginator\Adapter\Model;

class PoController extends ControllerBase{

  /**
   * @var string The namespace of the models used in the controller.
   */
  //private $modelNamespace = 'Triton\Purchases\Models';
  private $modelNamespace = '';

  /**
   * 
   * 
   */
  public function indexAction(){
    // Scope
    $scope = $this->request->getQuery('scope', 'string');
    $this->view->setVar('scope', $scope);
    // Pass the general parameters to the view.
    if ( $scope == 'global' ) {
      $this->view->setVar('title', 'New Global Purchase Order');
    }
    else {
      $this->view->setVar('title', 'New Purchase Order');
    }
    $this->view->setVar('subtitle', '');
    // Define controls.
    $this->view->setVar('show_submit', TRUE);
    $this->view->setVar('submit_text', 'Save');
    $this->view->setVar('show_cancel', TRUE);
    $this->view->setVar('cancel_text', "Cancel");
    $this->view->setVar('main_form_id', 'new-purchase-order-form');
    $this->view->setVar('exit_to', $this->url->getBaseUri() . 'index');
    // Form Lists.
    $this->view->setVar('shipment_terms_list', ShipmentTerm::find());
    $this->view->setVar('delivery_type_list', DeliveryType::find());
    $this->view->setVar('payment_terms_list', PaymentTerm::find());
    $this->view->setVar('freight_forwarders_list', FreightForwarder::find());
    $this->view->setVar('wiring_instructions_list', WiringInstruction::find());
    // Form predefined fields.
    $this->view->setVar('now', date('Y-m-d'));
  }

  /**
   * Action executed when submit the new PO form. Saves the purchase order.
   * 
   */
  public function newAction(){
    // Instantiate model object.
    $po = new PurchaseOrder();
    // Transform the post parameters in DB fields.
    $post_array = $this->request->getPost();
    // Fix the date to have right value and avoid fraud.
    $post_array['date'] = date('Y-m-d h:i:s');
    // Other calculated values.
    $post_array['exchange_rate'] = 1;
    $post_array['user'] = 'admin';
    $post_array['status'] = 'pending';
    $post_array['approval_status'] = 'pending';
    $post_array['total'] = 0;
    // Store and check for errors.
    $success = $po->save($post_array);
    // Results.
    if ($success) {
      // Forward flow to the edit action.
      $this->dispatcher->forward(array(
          "action" => "edit"
          ,"params" => array($po->poid)
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
   * Action executed when listing POs.
   * 
   */
  public function searchAction(){
    // Scope
    $scope = $this->request->getQuery('scope', 'string');
    $this->view->setVar('scope', $scope);
    // Pass the general parameters to the view.
    $this->view->setVar('title', ucfirst($scope) . ' Purchase Order Results');
    $this->view->setVar('subtitle', '');
    // @todo::: Define controls.
    $this->view->setVar('show_submit', TRUE);
    $this->view->setVar('submit_text', 'Add New');
    $this->view->setVar('show_cancel', TRUE);
    $this->view->setVar('cancel_text', "New Search");
    $this->view->setVar('main_form_id', '');
    $this->view->setVar('route_to', $this->url->getBaseUri() . 'po/index');
    $this->view->setVar('exit_to', $this->url->getBaseUri() . 'po/list');
    // Current page to show
    $currentPage = $this->request->getQuery('page', 'int');
    // Get the search criteria.
    $sPoid = $this->request->getPost('poid', 'int');
    $sSupplier = $this->request->getPost('supplier', 'int');
    $sFrom = $this->request->getPost('date_from', 'int');
    $sTo = $this->request->getPost('date_to', 'int');
    $sUnapproved = $this->request->getPost('unapproved', 'int');
    $sConsignment = $this->request->getPost('consignment', 'int');
    // Construct the query.
    $conditions = ' 1=1 ';
    if ( $sPoid ) {
      $conditions .= " AND poid = " . $sPoid;
    }
    if ( $sSupplier ) {
      $conditions .= " AND supplier = '" . $sSupplier . "'";
    }
    if ( $sFrom ) {
      $conditions .= " AND date >= '" . $sFrom . "'";
    }
    if ( $sTo ) {
      $conditions .= " AND date <= '" . $sTo . "'";
    }
    if ( $sUnapproved ) {
      $conditions .= " AND approval_status = 'pending'";
    }
    if ( $sConsignment ) {
      $conditions .= " AND status = 'pending'";
    }
    // Scope
    if ( $scope ) {
      if ( $scope == 'global' ) {
        $conditions .= " AND global = 'yes'";
      }
      elseif ( $scope == 'unapproved' ) {
        $conditions .= " AND approval_status != 'approved'";
      }
      elseif ( $scope == 'pending' ) {
        $conditions .= " AND status != 'approved'";
      }
    }
    // The data set to paginate
    // @todo::: Use a QueryBuilder. This is not optimized for pagination.
    $pos = PurchaseOrder::find(array(
      $conditions
      , 'order' => 'poid DESC' 
    ));
    // Instantiate the Query
    $paginator = new Model(
        array(
            "data" => $pos,
            "limit"=> 20,
            "page" => $currentPage
        )
    );
    // Get the paginated results
    $page = $paginator->getPaginate();
    // Post processing.
    foreach ($page->items as $key => $value) {
      // Date format.
      $value->date = date('m/d/Y', strtotime($value->date));
      // Supplier name.
      if ( $value->supplier ) {
        $company = Companies::findFirst(array(
          "company_id = " . $value->supplier
          , 'columns' => 'company_name'
        ));
        $value->supplier_name = $company->company_name;
      }
    }
    // Pass it to the view.
    $this->view->setVar('page', $page);
  }

  /**
   * Action executed when editing a PO.
   * 
   */
  public function editAction(){
    // The poid can come as a get parameter or as a parameter in dispatcher.
    $currentPO = $this->request->getQuery('po', 'int');
    //
    if (!$currentPO) {
      $params = $this->dispatcher->getParams();
      $currentPO = $params[0];
    }
    // Get the purchase order info.
    $po = PurchaseOrder::findFirst(array("poid = " . $currentPO));
    // Pass the general parameters to the view.
    if ( $po->global == 'yes' ) {
      $this->view->setVar('title', 'Global Order #' . $currentPO . ' ');
    }
    else {
      $this->view->setVar('title', 'Order #' . $currentPO . ' ');
    }
    // Pass the general parameters to the view.

    $this->view->setVar('subtitle', 'created: ' . date(DATE_RFC2822, strtotime($po->date)));
    // @todo::: Define controls.
    $this->view->setVar('show_submit', TRUE);
    $this->view->setVar('submit_text', 'Save');
    $this->view->setVar('show_cancel', TRUE);
    $this->view->setVar('cancel_text', "Cancel");
    $this->view->setVar('main_form_id', 'po-add-products-form');
    $this->view->setVar('exit_to', $this->url->getBaseUri() . 'po/search');
    // Form Lists.
    $this->view->setVar('units_list', Units::find());
    // Some info for the view.
    $supplier = Companies::findFirst(array("company_id = " . $po->supplier));
    $ship_to = Branch::findFirst(array("bid = " . $po->ship_to_location));
    $location = Branch::findFirst(array("bid = " . $po->purchase_location));
    $phql = 'SELECT pop.ppid, pop.so, p.pid, p.name, p.sku, pop.description, pop.quantity, pop.units, pop.unit_price, pop.extended, pop.slabs_data, pop.slabs_total, p.type FROM ' . $this->modelNamespace . '\PoProduct pop, ' 
            . $this->modelNamespace . '\Product p WHERE p.pid = pop.product AND pop.purchase_order = ' . $po->poid;
    $query = $this->modelsManager->createQuery($phql);
    $products = $query->execute();
    $products_new = array();
    foreach ($products as $key => $prod) {
      if ( $prod->slabs_data ) {
        $prod->bundles = json_decode($prod->slabs_data);
        //$prod->bundles = $prod->slabs_data;
      }
      else{
        $prod->bundles = FALSE;
      }
      $products_new[$key] = $prod;
    }
    $invoices = PoInvoice::find(array('conditions' => 'purchase_order = ' . $po->poid));
    // Send to view
    $this->view->setVar('po', $po);
    $this->view->setVar('supplier', $supplier);
    $this->view->setVar('ship_to', $ship_to);
    $this->view->setVar('location', $location);
    $this->view->setVar('products', $products_new);
    $this->view->setVar('invoices', $invoices);
  }

  /**
   * Action executed when showing the search form.
   * 
   */
  public function listAction(){
    // Scope
    $scope = $this->request->getQuery('scope', 'string');
    $this->view->setVar('scope', $scope);
    // Pass the general parameters to the view.
    $this->view->setVar('title', 'Purchase Orders');
    $this->view->setVar('subtitle', '');
    // @todo::: Define controls.
    $this->view->setVar('show_submit', FALSE);
    //$this->view->setVar('submit_text', 'Submit');
    $this->view->setVar('show_cancel', FALSE);
    //$this->view->setVar('cancel_text', "Cancel");
    $this->view->setVar('main_form_id', 'search_po_form');
    $this->view->setVar('exit_to', $this->url->getBaseUri() . 'po/search');
  }

  /**
   * Ajax service.
   * 
   * @param type $parameters
   * @return type
   */
  public function addProductAjax($parameters){
    // Parameters.
    $poid = $parameters['poid'];
    $product_id = $parameters['product'];
    $so = $parameters['so'];
    $quantity = $parameters['quantity'];
    $unit_price = $parameters['unit_price'];
    $extended = $parameters['extended'];
    $description = $parameters['description'];
    $note = $parameters['note'];
    $pack_quant = $parameters['pack_quant'];
    $pack_unit = $parameters['pack_unit'];
    $each_pack_unit = $parameters['each_pack_unit'];
    $units = $parameters['units'];
    $slabs_data = $parameters['slabs_data'];
    // Add.
    $result = $this->addProduct(
      $poid
      , $product_id
      , $so
      , $quantity
      , $unit_price
      , $extended
      , $description
      , $note
      , $pack_quant
      , $pack_unit
      , $each_pack_unit
      , $units
      , $slabs_data 
    );
    // R.
    return $result;
  }

  /**
   * Ajax service.
   * 
   * @param type $parameters
   * @return type
   */
  public function deleteProductAjax($parameters){
    // Parameters.
    $ppid = $parameters['ppid'];
    // Add.
    $result = $this->deleteProduct($ppid);
    // R.
    return $result;
  }

  /**
   * Ajax service.
   * 
   * @param type $parameters
   * @return type
   */
  public function addInvoiceAjax($parameters){
    // Parameters.
    $poid = $parameters['poid'];
    $transaction_name = $parameters['transaction_name'];
    $number = $parameters['number'];
    $container_number = $parameters['container_number'];
    $eta_date = $parameters['eta_date'];
    $total = $parameters['total'];
    $received_date = $parameters['received_date'];
    // Add.
    $result = $this->addInvoice(
      $poid
      ,$transaction_name
      ,$number 
      ,$container_number 
      ,$eta_date 
      ,$total 
      ,$received_date 
    );
    // R.
    return $result;
  }

  /**
   * Ajax service.
   * 
   * @param type $parameters
   * @return type
   */
  public function approvePoAjax($parameters){
    // Parameters.
    $poid = $parameters['poid'];
    // Add.
    $result = $this->approvePo($poid);
    // R.
    return $result;
  }

  /**
   * Adds a product to a PO.
   * 
   * @param int $poid The id of the PO.
   * @param int $product_id The id of the product to add.
   * @param string $so
   * @param float $quantity 
   * @param float $unit_price 
   * @param float $extended 
   */
  protected function addProduct(
    $poid
    , $product_id
    , $so
    , $quantity
    , $unit_price
    , $extended
    , $description
    , $note
    , $pack_quant
    , $pack_unit
    , $each_pack_unit
    , $units
    , $slabs_data = NULL
  ){
    // Create the object and save it.
    $pop = new PoProduct();
    $pop->save(array(
      "purchase_order" => $poid
      , "product" => $product_id
      , "so" => $so
      , "quantity" => $quantity
      , "unit_price" => $unit_price
      , "extended" => $extended
      , "description" => $description
      , "notes" => $note
      , "slabs_data" => json_encode($slabs_data)
      , "slabs_total" => $slabs_data['count']
      , "pack_quant" => $pack_quant
      , "pack_unit" => $pack_unit
      , "each_pack_unit" => $each_pack_unit
      , "units" => $units
    ));
    // @todo::: Save the slabs_data.
    // Update the total and return it.
    $newTotal = $this->recalculatePoTotal($poid);
    // R
    return $newTotal;
  }

  /**
   * Adds a invoice to a PO.
   * 
   */
  protected function addInvoice(
    $poid
    ,$transaction_name 
    ,$number 
    ,$container_number 
    ,$eta_date 
    ,$total 
    ,$received_date 
  ){
    // Create the object and save it.
    $invoice = new PoInvoice();
    //$invoice->save();
    $invoice->save(array(
      "purchase_order" => $poid
      , "date" => date('Y-m-d h:m:i')
      , "number" => $number
      , "total" => $total
      , "eta_date" => $eta_date
      , "received_date" => $received_date
      , "container_number" => $container_number
      , "status" => 'inserted'
      , "description" => $transaction_name
    ));
    // R
    return $invoice->date;
  }

  /**
   * Approves a PO.
   * 
   */
  protected function approvePo($poid){
    // Find the po.
    $po = PurchaseOrder::findFirst( array( 'conditions' => "poid = " . $poid ) );
    // Update.
    $po->approval_status = 'approved';
    $po->save();
    // R
    return 1;
  }

  /**
   * Deletes a product from a PO.
   * 
   * @param int $ppid The po_product id.
   */
  protected function deleteProduct($ppid){
    // Find.
    $pop = PoProduct::findFirst(array('ppid = ' . $ppid));
    // Get the PO ID.
    $poid = $pop->purchase_order;
    // Delete.
    $pop->delete();
    // Update the total and return it.
    $newTotal = $this->recalculatePoTotal($poid);
    // R
    return $newTotal;
  }

  /**
   * Checks all the products associated to a PO, calculates the total and 
   * updates the total field.
   * 
   * @param type $poid
   */
  public function recalculatePoTotal($poid){
    // Get the products.
    $products = PoProduct::find(array('purchase_order = ' . $poid));
    $total = 0;
    // Sum.
    foreach ($products as $value) {
      $total += $value->extended;
    }
    // Update.
    $po = PurchaseOrder::findFirst(array('poid = ' . $poid));
    $po->total = $total;
    $po->save();
    // R.
    return $total;
  }
    
  /**
   * Ajax service.
   * 
   * @param type $parameters
   * @return type
   */
  public function fulfillProductsAjax($parameters){
    // Parameters.
    $products = $parameters['products'];
    $returnStr = "";
    // Save the inventory.
    foreach ($products as $key => $value) {
      $returnStr .= "[" . $key . "]" . $value['pid'] . ', ';
      // Check if it exists.
      $inv = Inventory::findFirst(array('product = ' . $value['pid']));
      // Update.
      if ( $inv ) {
        $returnStr .= $value['type'] . "--";
        // Slabs
        if ( $value['type'] == 1 ) {
          $slabs_data = json_decode($value['slabs_data']);
          if ( $slabs_data && isset($slabs_data->count) && isset($slabs_data->bundles) ) {
            // Amount.
            $inv->amount = $inv->amount + $slabs_data->count;
            // New elements for the detail.
            $cur_slabs_data = json_decode($inv->detail);
            $cur_slabs_data = array_merge($cur_slabs_data, $slabs_data->bundles);
            $inv->detail = json_encode($cur_slabs_data);
          }
        }
        // Others.
        else {
          // Amount.
          $inv->amount = $inv->amount + $value['amount'];
        }
        // Save.
        $inv->save();
      }
      // Create.
      else {
        $save_array = array(
          "product" => $value['pid'],
        );
        // Slabs
        if ( $value['type'] == 1 ) {
          $slabs_data = json_decode($value['slabs_data']);
          if ( $slabs_data && isset($slabs_data->count) && isset($slabs_data->bundles) ) {
            $save_array['amount'] = $slabs_data->count;
            $save_array['detail'] = json_encode($slabs_data->bundles);
          }
          else {
            $save_array['amount'] = 0;
            $save_array['detail'] = "";
          }
        }
        else {
          //$save_array['amount'] = $value['amount'];
        }
        // Save New row.
        $inv = new Inventory();
        $inv->save($save_array);
      }
    }
    // R.
    return TRUE;
  }
}

