<?php
namespace Triton\Bo\Sales;

/**
 * Business logic for the salesorder in the sales catalog.
 *
 * @author juangalf
 */
class SalesOrderBO {
  
  /**
   * Returns the list of salesorder.
   * 
   * @param object $app The Phalcon Micro application.
   * @param array $query Array of filters.
   * @return object The salesorder detail.
   */
  public static function getAllSalesOrder($app, $query) {
    try{
      // Query.
      $phql = "SELECT soid
          , sales_order_number
          , date
          , customer_po_number
          , location
          , billing_customer
          , billing_attention
          , billing_address
          , billing_unit_number
          , billing_city
          , billing_state
          , billing_zip
          , billing_country
          , billing_phone
          , billing_fax
          , billing_mobile
          , billing_email
          , payment_terms
          , price_level
          , primary_sales_person
          , ship_to
          , sales_tax
          , pickup_date
          , ship_to_company
          , ship_to_attention
          , ship_to_name
          , ship_to_address
          , ship_to_unit_number
          , ship_to_city
          , ship_to_state
          , ship_to_zip
          , ship_to_country
          , ship_to_phone
          , ship_to_fax
          , ship_to_mobile
          , ship_to_email
          , geocode_latitude
          , geocode_longitude
          , referred_by
          , special_delivery_instructions
          , internal_notes
          , printed_notes
          , status
          , b.name as brnach_name  FROM Triton\Models\Sales\SalesOrder s
          ,Triton\Models\Actor\Branch b WHERE
          s.location = b.bid ";
      $pageSize = isset($query['pageSize']) ? $query['pageSize']: 20;
      $page = isset($query['page']) ? $query['page'] : "All";
      
      $varsArray = array();
      if ( isset($query['sales_order_number']) && !empty($query['sales_order_number'])) {
        $phql .= " AND s.sales_order_number LIKE :sales_order_number:";
        $varsArray['sales_order_number'] = '%' . $query['sales_order_number'] . '%';
      }
      if ( isset($query['date'])  && !empty($query['date']) ) {
        $phql .= " AND s.date LIKE :date:";
        $varsArray['date'] =  '%' .$query['date']. '%' ;
      }
      if ( isset($query['billing_customer'])  && !empty($query['billing_customer']) ) {
        $phql .= " AND s.billing_customer=:billing_customer:";
        $varsArray['billing_customer'] = $query['billing_customer'] ;
      }
      if ( isset($query['ship_to'])  && !empty($query['ship_to']) ) {
        $phql .= " AND s.ship_to =:ship_to:";
        $varsArray['ship_to'] = $query['ship_to'];
      }
      if ( isset($query['primary_sales_person'])  && !empty($query['primary_sales_person']) ) {
        $phql .= " AND s.primary_sales_person LIKE :primary_sales_person:";
        $varsArray['primary_sales_person'] = '%' . $query['primary_sales_person'] . '%';
      }
     
      $saleOrders = $app->modelsManager->executeQuery($phql, $varsArray);
      // Create a Model paginator, show 10 rows by page starting from $currentPage
      $list = array();
      if($page != "All"){
          $paginator = new \Phalcon\Paginator\Adapter\Model(
          array(
              "data" => $saleOrders,
              "limit"=> $pageSize,
              "page" => $page
          )
        );
        $page = $paginator->getPaginate();
            foreach ($page->items as $saleOrder) {
                // Add some complex fields.

                $saleOrder->location_n = $saleOrder->brnach_name; 
                $saleOrder->primary_sales_person_n = $saleOrder->primary_sales_person; 
                $phql = "SELECT * FROM Triton\Models\Actor\Companies c WHERE c.company_id=:company_id:";
                $item = $app->modelsManager->executeQuery($phql, array("company_id"=>$saleOrder->billing_customer))->getFirst();
                $saleOrder->billing_customer_n = $item->company_name; 
                
                $phql = "SELECT  t.tid ,t.name FROM Triton\Models\Taxonomy\TaxonomyTerm t  WHERE t.tid = :tid:";
                $item = $app->modelsManager->executeQuery($phql, array("tid"=>$saleOrder->payment_terms))->getFirst();
                $saleOrder->payment_terms_n = $item->name; 
                
                $phql = "SELECT  t.tid ,t.name FROM Triton\Models\Taxonomy\TaxonomyTerm t  WHERE t.tid = :tid:";
                $item = $app->modelsManager->executeQuery($phql, array("tid"=>$saleOrder->sales_tax))->getFirst();
                $saleOrder->sales_tax_n = $item->name;
                
                if($saleOrder->price_level > 0){
                    $phql = "SELECT  t.tid ,t.name FROM Triton\Models\Taxonomy\TaxonomyTerm t  WHERE t.tid = :tid:";
                    $item = $app->modelsManager->executeQuery($phql, array("tid"=>$saleOrder->price_level))->getFirst();
                    $saleOrder->price_level_n = $item->name; 
                }
                if($saleOrder->ship_to_company > 0){
                    $phql = "SELECT * FROM Triton\Models\Actor\Companies c WHERE c.company_id=:company_id:";
                    $item = $app->modelsManager->executeQuery($phql, array("company_id"=>$saleOrder->ship_to_company))->getFirst();
                    $saleOrder->ship_to_company_n = $item->company_name; 
                }
                if($saleOrder->referred_by > 0){
                    $phql = "SELECT * FROM Triton\Models\Actor\Companies c WHERE c.company_id=:company_id:";
                    $item = $app->modelsManager->executeQuery($phql, array("company_id"=>$saleOrder->referred_by))->getFirst();
                    $saleOrder->referred_by_n = $item->company_name; 
                }
                $phql = "SELECT sum(quantity*unit_price) as total FROM Triton\Models\Sales\SoProduct sp WHERE sp.soid=:soid:";
                $item = $app->modelsManager->executeQuery($phql, array("soid"=>$saleOrder->soid))->getFirst();
                $saleOrder->total = $item->total; 
                
                $pos = strpos($saleOrder->status, "Fulfillment");
                if($saleOrder->status == "100% Fulfillment"){
                   $saleOrder->statusClass = "status-100-fulfilled"; 
                }else if($pos !== false){
                   $saleOrder->statusClass = "status-partial-fulfilled";  
                }else if($saleOrder->status == "Closed"){
                   $saleOrder->statusClass = "status-closed";  
                }else{
                   $saleOrder->statusClass = "status-other";   
                }
                $list[] = $saleOrder;
            }
      
      }else{
          $list = $saleOrders;
      }
      
      // Get the paginated results
      //
      
      //foreach ($products as $product) {
      
      // Prepare the return object.
      $data = array(
        "status" => "OK",
        "pager" => $page,
        "list" => $list,
      );
      // R.
      return $data;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
  
  /**
   * Returns the detail of an specific saels order.
   * 
   * @param object $app The Phalcon Micro application.
   * @param int $id The ID of the product to query.
   * @return object The salesorder detail.
   */
  public static function getDetail($app, $soid) {
    try{
        // Query.
        $phql = "SELECT soid
          , sales_order_number
          , date
          , customer_po_number
          , location
          , billing_customer
          , billing_attention
          , billing_address
          , billing_unit_number
          , billing_city
          , billing_state
          , billing_zip
          , billing_country
          , billing_phone
          , billing_fax
          , billing_mobile
          , billing_email
          , payment_terms
          , price_level
          , primary_sales_person
          , ship_to
          , sales_tax
          , pickup_date
          , ship_to_company
          , ship_to_attention
          , ship_to_name
          , ship_to_address
          , ship_to_unit_number
          , ship_to_city
          , ship_to_state
          , ship_to_zip
          , ship_to_country
          , ship_to_phone
          , ship_to_fax
          , ship_to_mobile
          , ship_to_email
          , geocode_latitude
          , geocode_longitude
          , referred_by
          , special_delivery_instructions
          , internal_notes
          , printed_notes
          , status
          , b.name as brnach_name  FROM Triton\Models\Sales\SalesOrder s
          ,Triton\Models\Actor\Branch b WHERE
          s.location = b.bid AND s.soid=:soid:";
        $saleOrder = $app->modelsManager->executeQuery($phql, array(
          'soid' => $soid
        ))->getFirst();
        $saleOrder->location_n = $saleOrder->brnach_name; 
        $saleOrder->primary_sales_person_n = $saleOrder->primary_sales_person;
        $phql = "SELECT * FROM Triton\Models\Actor\Companies c WHERE c.company_id=:company_id:";
        $item = $app->modelsManager->executeQuery($phql, array("company_id"=>$saleOrder->billing_customer))->getFirst();
        $saleOrder->billing_customer_n = $item->company_name; 
         

        $phql = "SELECT  t.tid ,t.name FROM Triton\Models\Taxonomy\TaxonomyTerm t  WHERE t.tid = :tid:";
        $item = $app->modelsManager->executeQuery($phql, array("tid"=>$saleOrder->payment_terms))->getFirst();
        $saleOrder->payment_terms_n = $item->name; 

        $phql = "SELECT  t.tid ,t.name FROM Triton\Models\Taxonomy\TaxonomyTerm t  WHERE t.tid = :tid:";
        $item = $app->modelsManager->executeQuery($phql, array("tid"=>$saleOrder->sales_tax))->getFirst();
        $saleOrder->sales_tax_n = $item->name;

        if($saleOrder->price_level > 0){
            $phql = "SELECT  t.tid ,t.name FROM Triton\Models\Taxonomy\TaxonomyTerm t  WHERE t.tid = :tid:";
            $item = $app->modelsManager->executeQuery($phql, array("tid"=>$saleOrder->price_level))->getFirst();
            $saleOrder->price_level_n = $item->name; 
        }
        if($saleOrder->ship_to_company > 0){
            $phql = "SELECT * FROM Triton\Models\Actor\Companies c WHERE c.company_id=:company_id:";
            $item = $app->modelsManager->executeQuery($phql, array("company_id"=>$saleOrder->ship_to_company))->getFirst();
            $saleOrder->ship_to_company_n = $item->company_name; 
        }
        if($saleOrder->referred_by > 0){
            $phql = "SELECT * FROM Triton\Models\Actor\Companies c WHERE c.company_id=:company_id:";
            $item = $app->modelsManager->executeQuery($phql, array("company_id"=>$saleOrder->referred_by))->getFirst();
            $saleOrder->referred_by_n = $item->company_name; 
        }
      return $saleOrder;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
  
  /**
   * Creates a new sales order.
   * 
   * @param object $app The Phalcon Micro application.
   * @param object $salesOrder The sales order object with all the data.
   * @return object The response of the query execution $app->modelsManager->executeQuery.
   */
  public static function create($app, $salesOrder) {
    try{
      // Insert.
      $phql = "INSERT INTO Triton\Models\Sales\SalesOrder ( 
          sales_order_number
          , date
          , customer_po_number
          , location
          , billing_customer
          , billing_attention
          , billing_address
          , billing_unit_number
          , billing_city
          , billing_state
          , billing_zip
          , billing_country
          , billing_phone
          , billing_fax
          , billing_mobile
          , billing_email
          , payment_terms
          , price_level
          , primary_sales_person
          , ship_to
          , sales_tax
          , pickup_date
          , ship_to_company
          , ship_to_attention
          , ship_to_name
          , ship_to_address
          , ship_to_unit_number
          , ship_to_city
          , ship_to_state
          , ship_to_zip
          , ship_to_country
          , ship_to_phone
          , ship_to_fax
          , ship_to_mobile
          , ship_to_email
          , geocode_latitude
          , geocode_longitude
          , referred_by
          , special_delivery_instructions
          , internal_notes
          , printed_notes
          , status
         )
        VALUES (
          :sales_order_number:
          , :date:
          , :customer_po_number:
          , :location:
          , :billing_customer:
          , :billing_attention:
          , :billing_address:
          , :billing_unit_number:
          , :billing_city:
          , :billing_state:
          , :billing_zip:
          , :billing_country:
          , :billing_phone:
          , :billing_fax:
          , :billing_mobile:
          , :billing_email:
          , :payment_terms:
          , :price_level:
          , :primary_sales_person:
          , :ship_to:
          , :sales_tax:
          , :pickup_date:
          , :ship_to_company:
          , :ship_to_attention:
          , :ship_to_name:
          , :ship_to_address:
          , :ship_to_unit_number:
          , :ship_to_city:
          , :ship_to_state:
          , :ship_to_zip:
          , :ship_to_country:
          , :ship_to_phone:
          , :ship_to_fax:
          , :ship_to_mobile:
          , :ship_to_email:
          , :geocode_latitude:
          , :geocode_longitude:
          , :referred_by:
          , :special_delivery_instructions:
          , :internal_notes:
          , :printed_notes:
          , :status:
        )
      ";
      $status = $app->modelsManager->executeQuery($phql, array(
        'sales_order_number' => $salesOrder->sales_order_number
        ,'date' => $salesOrder->date
        ,'customer_po_number' => isset($salesOrder->customer_po_number)? $salesOrder->customer_po_number: NULL
        ,'location' => $salesOrder->location
        ,'billing_customer' => $salesOrder->billing_customer
        ,'billing_attention' => isset($salesOrder->billing_attention)? $salesOrder->billing_attention: NULL
        ,'billing_address' => isset($salesOrder->billing_address)? $salesOrder->billing_address: NULL
        ,'billing_unit_number' => isset($salesOrder->billing_unit_number)? $salesOrder->billing_unit_number: NULL
        ,'billing_city' => isset($salesOrder->billing_city)? $salesOrder->billing_city: NULL
        ,'billing_state' => isset($salesOrder->billing_state)? $salesOrder->billing_state: NULL
        ,'billing_zip' => isset($salesOrder->billing_zip)? $salesOrder->billing_zip: NULL
        ,'billing_country' => isset($salesOrder->billing_country)? $salesOrder->billing_country: NULL
        ,'billing_phone' => isset($salesOrder->billing_phone)? $salesOrder->billing_phone: NULL
        ,'billing_fax' => isset($salesOrder->billing_fax)? $salesOrder->billing_fax: NULL
        ,'billing_mobile' => isset($salesOrder->billing_mobile)? $salesOrder->billing_mobile: NULL
        ,'billing_email' => isset($salesOrder->billing_email)? $salesOrder->billing_email: NULL
        ,'payment_terms' => $salesOrder->payment_terms
        ,'price_level' => isset($salesOrder->price_level)? $salesOrder->price_level: NULL
        ,'primary_sales_person' => $salesOrder->primary_sales_person
        ,'ship_to' => $salesOrder->ship_to
        ,'sales_tax' => $salesOrder->sales_tax
        ,'pickup_date' => $salesOrder->pickup_date
        ,'ship_to_company' => isset($salesOrder->ship_to_company)? $salesOrder->ship_to_company: NULL
        ,'ship_to_name' => isset($salesOrder->ship_to_name)? $salesOrder->ship_to_name: NULL
        ,'ship_to_attention' => isset($salesOrder->ship_to_attention)? $salesOrder->ship_to_attention: NULL
        ,'ship_to_address' => isset($salesOrder->ship_to_address)? $salesOrder->ship_to_address: NULL
        ,'ship_to_unit_number' => isset($salesOrder->ship_to_unit_number)? $salesOrder->ship_to_unit_number: NULL
        ,'ship_to_city' => isset($salesOrder->ship_to_city)? $salesOrder->ship_to_city: NULL
        ,'ship_to_state' => isset($salesOrder->ship_to_state)? $salesOrder->ship_to_state: NULL
        ,'ship_to_zip' => isset($salesOrder->ship_to_zip)? $salesOrder->ship_to_zip: NULL
        ,'ship_to_country' => isset($salesOrder->ship_to_country)? $salesOrder->ship_to_country: NULL
        ,'ship_to_phone' => isset($salesOrder->ship_to_phone)? $salesOrder->ship_to_phone: NULL
        ,'ship_to_fax' => isset($salesOrder->ship_to_fax)? $salesOrder->ship_to_fax: NULL
        ,'ship_to_mobile' => isset($salesOrder->ship_to_mobile)? $salesOrder->ship_to_mobile: NULL
        ,'ship_to_email' => isset($salesOrder->ship_to_email)? $salesOrder->ship_to_email: NULL
        ,'geocode_latitude' => isset($salesOrder->geocode_latitude)? $salesOrder->geocode_latitude: NULL
        ,'geocode_longitude' => isset($salesOrder->geocode_longitude)? $salesOrder->geocode_longitude: NULL
        ,'referred_by' => isset($salesOrder->referred_by)? $salesOrder->referred_by: NULL
        ,'special_delivery_instructions' => isset($salesOrder->special_delivery_instructions)? $salesOrder->special_delivery_instructions: NULL
        ,'internal_notes' => isset($salesOrder->internal_notes)? $salesOrder->internal_notes: NULL
        ,'printed_notes' => isset($salesOrder->printed_notes)? $salesOrder->printed_notes: NULL
        ,'status' => '0% Fulfillment'
       
      ));
      $app->logger->log(get_class($e) . ": [" . $status->getModel()->soid. '] >>>>> at ' );

      //$status2 = $app->modelsManager->executeQuery("UPDATE Triton\Models\Sales\SalesOrderNumbers so SET so.is_used=:is_used: WHERE so.so_number=:so_number:", array('is_used' => '1', 'so_number' => $salesOrder->sales_order_number));
     // R.
      return $status;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
 
  /**
   * Update an existing sales order.
   * 
   * @param object $app The Phalcon Micro application.
   * @param object $salesOrder The sales order object with all the data.
   * @return object The response of the query execution $app->modelsManager->executeQuery.
   */
  public static function update($app, $salesOrder) {
    try{
      // Insert.
      $phql = "UPDATE Triton\Models\Sales\SalesOrder SET 
           sales_order_number = :sales_order_number:
          , date = :date:
          , customer_po_number = :customer_po_number:
          , location = :location:
          , billing_customer = :billing_customer:
          , billing_attention = :billing_attention:
          , billing_address = :billing_address:
          , billing_unit_number =:billing_unit_number:
          , billing_city = :billing_city:
          , billing_state = :billing_state:
          , billing_zip = :billing_zip:
          , billing_country = :billing_country:
          , billing_phone = :billing_phone:
          , billing_fax = :billing_fax:
          , billing_mobile = :billing_mobile:
          , billing_email = :billing_email:
          , payment_terms = :payment_terms:
          , price_level = :price_level:
          , primary_sales_person = :primary_sales_person:
          , ship_to = :ship_to:
          , sales_tax = :sales_tax:
          , pickup_date = :pickup_date:
          , ship_to_company = :ship_to_company:
          , ship_to_attention = :ship_to_attention:
          , ship_to_name = :ship_to_name:
          , ship_to_address = :ship_to_address:
          , ship_to_unit_number = :ship_to_unit_number:
          , ship_to_city = :ship_to_city:
          , ship_to_state = :ship_to_state:
          , ship_to_zip = :ship_to_zip:
          , ship_to_country = :ship_to_country:
          , ship_to_phone = :ship_to_phone:
          , ship_to_fax = :ship_to_fax:
          , ship_to_mobile = :ship_to_mobile:
          , ship_to_email = :ship_to_email:
          , geocode_latitude = :geocode_latitude:
          , geocode_longitude = :geocode_longitude:
          , referred_by = :referred_by:
          , special_delivery_instructions = :special_delivery_instructions:
          , internal_notes = :internal_notes:
          , printed_notes = :printed_notes:
         WHERE soid = :soid: 
      ";
      $status = $app->modelsManager->executeQuery($phql, array(
         'soid' => $salesOrder->soid
        ,'sales_order_number' => $salesOrder->sales_order_number
        ,'date' => $salesOrder->date
        ,'customer_po_number' => isset($salesOrder->customer_po_number)? $salesOrder->customer_po_number: NULL
        ,'location' => $salesOrder->location
        ,'billing_customer' => $salesOrder->billing_customer
        ,'billing_attention' => isset($salesOrder->billing_attention)? $salesOrder->billing_attention: NULL
        ,'billing_address' => isset($salesOrder->billing_address)? $salesOrder->billing_address: NULL
        ,'billing_unit_number' => isset($salesOrder->billing_unit_number)? $salesOrder->billing_unit_number: NULL
        ,'billing_city' => isset($salesOrder->billing_city)? $salesOrder->billing_city: NULL
        ,'billing_state' => isset($salesOrder->billing_state)? $salesOrder->billing_state: NULL
        ,'billing_zip' => isset($salesOrder->billing_zip)? $salesOrder->billing_zip: NULL
        ,'billing_country' => isset($salesOrder->billing_country)? $salesOrder->billing_country: NULL
        ,'billing_phone' => isset($salesOrder->billing_phone)? $salesOrder->billing_phone: NULL
        ,'billing_fax' => isset($salesOrder->billing_fax)? $salesOrder->billing_fax: NULL
        ,'billing_mobile' => isset($salesOrder->billing_mobile)? $salesOrder->billing_mobile: NULL
        ,'billing_email' => isset($salesOrder->billing_email)? $salesOrder->billing_email: NULL
        ,'payment_terms' => $salesOrder->payment_terms
        ,'price_level' => isset($salesOrder->price_level)? $salesOrder->price_level: NULL
        ,'primary_sales_person' => $salesOrder->primary_sales_person
        ,'ship_to' => $salesOrder->ship_to
        ,'sales_tax' => $salesOrder->sales_tax
        ,'pickup_date' => $salesOrder->pickup_date
        ,'ship_to_company' => isset($salesOrder->ship_to_company)? $salesOrder->ship_to_company: NULL
        ,'ship_to_name' => isset($salesOrder->ship_to_name)? $salesOrder->ship_to_name: NULL
        ,'ship_to_attention' => isset($salesOrder->ship_to_attention)? $salesOrder->ship_to_attention: NULL
        ,'ship_to_address' => isset($salesOrder->ship_to_address)? $salesOrder->ship_to_address: NULL
        ,'ship_to_unit_number' => isset($salesOrder->ship_to_unit_number)? $salesOrder->ship_to_unit_number: NULL
        ,'ship_to_city' => isset($salesOrder->ship_to_city)? $salesOrder->ship_to_city: NULL
        ,'ship_to_state' => isset($salesOrder->ship_to_state)? $salesOrder->ship_to_state: NULL
        ,'ship_to_zip' => isset($salesOrder->ship_to_zip)? $salesOrder->ship_to_zip: NULL
        ,'ship_to_country' => isset($salesOrder->ship_to_country)? $salesOrder->ship_to_country: NULL
        ,'ship_to_phone' => isset($salesOrder->ship_to_phone)? $salesOrder->ship_to_phone: NULL
        ,'ship_to_fax' => isset($salesOrder->ship_to_fax)? $salesOrder->ship_to_fax: NULL
        ,'ship_to_mobile' => isset($salesOrder->ship_to_mobile)? $salesOrder->ship_to_mobile: NULL
        ,'ship_to_email' => isset($salesOrder->ship_to_email)? $salesOrder->ship_to_email: NULL
        ,'geocode_latitude' => isset($salesOrder->geocode_latitude)? $salesOrder->geocode_latitude: NULL
        ,'geocode_longitude' => isset($salesOrder->geocode_longitude)? $salesOrder->geocode_longitude: NULL
        ,'referred_by' => isset($salesOrder->referred_by)? $salesOrder->referred_by: NULL
        ,'special_delivery_instructions' => isset($salesOrder->special_delivery_instructions)? $salesOrder->special_delivery_instructions: NULL
        ,'internal_notes' => isset($salesOrder->internal_notes)? $salesOrder->internal_notes: NULL
        ,'printed_notes' => isset($salesOrder->printed_notes)? $salesOrder->printed_notes: NULL
       
      ));
      $app->logger->log(get_class($e) . ": [" . $status->getModel()->soid. '] >>>>> at ' );

      
     // R.
      return $status;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
  
  /**
   * Update status of an existing sales order.
   * 
   * @param object $app The Phalcon Micro application.
   * @param object $salesOrder The sales order object with all the data.
   * @return object The response of the query execution $app->modelsManager->executeQuery.
   */
  public static function updateStatus($app, $salesOrder) {
    try{
      // Insert.
      $phql = "UPDATE Triton\Models\Sales\SalesOrder SET 
           status = :status:
         WHERE soid = :soid:";
      $status = $app->modelsManager->executeQuery($phql, array(
         'soid' => $salesOrder->soid
        ,'status' => $salesOrder->status
      ));
      
     // R.
      return $status;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
  
  /**
   * Returns the next sales order number to be used
   * 
   * @param object $app The Phalcon Micro application.
   * @return next sales order number to be used.
   */
  public static function delete($app, $soid) {
    try{
      // Query.
      $phql = "DELETE  FROM Triton\Models\Sales\SalesOrder WHERE soid=:soid:";
      $status = $app->modelsManager->executeQuery($phql, array("soid" => $soid));
      return $status;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
  /**
   * Returns the next sales order number to be used
   * 
   * @param object $app The Phalcon Micro application.
   * @return next sales order number to be used.
   */
  public static function getNextSaleOrderNumber($app) {
    try{
      // Query.
      $phql = "SELECT count(so.so_number) as cnt FROM Triton\Models\Sales\SalesOrderNumbers so ";
      $result = $app->modelsManager->executeQuery($phql, array())->getFirst();
      $total = $result['cnt']; 
      $total++;
      $so_number = "SO-".sprintf('%04d', $total);
      $phql = "INSERT INTO Triton\Models\Sales\SalesOrderNumbers (is_used, so_number) VALUES (:is_used:, :so_number:)";
      $status = $app->modelsManager->executeQuery($phql, array('is_used' => '0', 'so_number' => $so_number));
      return $so_number;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
  
  public static function getSalesOrderProduct($app, $query){
    try{
      $phql = "SELECT sp.spid, sp.product, p.name, p.sku, sp.description, sp.quantity, u.name as uom, sp.unit_price, sp.extended, sp.is_taxable, sp.units, sp.notes FROM Triton\Models\Sales\SoProduct sp, Triton\Models\Inventory\Product p, Triton\Models\Inventory\Units u WHERE sp.product=p.pid AND sp.units=u.uid AND sp.soid=:soid:";
      $pageSize = isset($query['pageSize']) ? $query['pageSize']: 20;
      $page = isset($query['page']) ? $query['page'] : "All";
      $varsArray = array("soid"=> $query['soid']);
      $saleOrderProducts = $app->modelsManager->executeQuery($phql, $varsArray);
      // Create a Model paginator, show 10 rows by page starting from $currentPage
      
      $list = array();
      foreach ($saleOrderProducts as $item) {
        // Add it to the result.
        $list[] = $item;
      }
      // R.
      return $list;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
  
  public static function deleteSalesOrderProduct($app, $spid) {
    try{
      // Query.
      $phql = "DELETE  FROM Triton\Models\Sales\SoProduct WHERE spid=:spid:";
      $status = $app->modelsManager->executeQuery($phql, array("spid" => $spid));
      return $status;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
  
  public static function addSalesOrderProduct($app, $salesOrderProduct){
      try{
      // Insert.
      $phql = "INSERT INTO Triton\Models\Sales\SoProduct ( 
          product, soid, description, quantity, units, package_quantity, package_units, eachs_per_package_unit, unit_price, extended, notes, slabs_data, slabs_total, is_taxable
         )
        VALUES (
          :product: , :soid: , :description: , :quantity:, :units:, :package_quantity:, :package_units:
          , :eachs_per_package_unit: , :unit_price: , :extended:, :notes:, :slabs_data:, :slabs_total:, :is_taxable:     )
      ";
      $status = $app->modelsManager->executeQuery($phql, array(
      "product" => $salesOrderProduct->product
      , "soid" => $salesOrderProduct->soid
      , "quantity" => isset($salesOrderProduct->quantity)? $salesOrderProduct->quantity: NULL
      , "unit_price" => isset($salesOrderProduct->unit_price)? $salesOrderProduct->unit_price: NULL
      , "extended" => isset($salesOrderProduct->extended)? $salesOrderProduct->extended: NULL
      , "description" => isset($salesOrderProduct->description)? $salesOrderProduct->description: NULL
      , "notes" => isset($salesOrderProduct->notes)? $salesOrderProduct->notes: NULL
      , "slabs_data" => isset($salesOrderProduct->slabs_data)? $salesOrderProduct->slabs_data: NULL
      , "slabs_total" => isset($salesOrderProduct->slabs_total)? $salesOrderProduct->slabs_total: NULL
      , "package_quantity" => isset($salesOrderProduct->package_quantity)? $salesOrderProduct->package_quantity: NULL
      , "package_units" => isset($salesOrderProduct->package_units)? $salesOrderProduct->package_units: NULL
      , "eachs_per_package_unit" => isset($salesOrderProduct->eachs_per_package_unit)? $salesOrderProduct->eachs_per_package_unit: NULL
      , "units" => isset($salesOrderProduct->units)? $salesOrderProduct->units: NULL
      , "is_taxable" => isset($salesOrderProduct->is_taxable)? 'Yes': 'No'
    ));
      $app->logger->log(get_class($e) . ": [" . $status->getModel()->spid. '] >>>>> at ' );

     // R.
      return $status;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
  
  /**
   * Update status of an existing sales order.
   * 
   * @param object $app The Phalcon Micro application.
   * @param object $salesOrderProduct The sales order product object with all the data.
   * @return object The response of the query execution $app->modelsManager->executeQuery.
   */
  public static function updateSalesOrderProduct($app, $salesOrderProduct) {
    try{
      $phql = "UPDATE Triton\Models\Sales\SoProduct SET 
           quantity = :quantity:
           ,unit_price = :unit_price:
           ,extended = :extended:
           ,description = :description:
           ,notes = :notes:
           ,units = :units:
           ,is_taxable = :is_taxable:
         WHERE spid = :spid:";
      $status = $app->modelsManager->executeQuery($phql, array(
        'spid' => $salesOrderProduct->spid
        , "quantity" => isset($salesOrderProduct->quantity)? $salesOrderProduct->quantity: NULL
        , "unit_price" => isset($salesOrderProduct->unit_price)? $salesOrderProduct->unit_price: NULL
        , "extended" => isset($salesOrderProduct->extended)? $salesOrderProduct->extended: NULL
        , "description" => isset($salesOrderProduct->description)? $salesOrderProduct->description: NULL
        , "notes" => isset($salesOrderProduct->notes)? $salesOrderProduct->notes: NULL
        , "units" => isset($salesOrderProduct->units)? $salesOrderProduct->units: NULL
        , "is_taxable" => isset($salesOrderProduct->is_taxable)? 'Yes': 'No'
      ));
      
     // R.
      return $status;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
  
  
  /**
   * Returns the list of products.
   * 
   * @param object $app The Phalcon Micro application.
   * @param array $query Array of filters.
   * @return object The product detail.
   */
  public static function searchProducts($app, $query) {
    try{
      // Query.
      $phql = "SELECT 
          pid
          ,sku
          ,p.name
          ,type 
          ,t.name as typename
          ,units
          ,weight
          ,size
          ,price_1
          ,price_2
          ,price_3
          ,price_4
          ,inv.amount as amount
          ";
      // Join with inventory if that the request.
      $phql .= "
        FROM Triton\Models\Inventory\Product p, 
        Triton\Models\Inventory\Inventory inv,
        Triton\Models\Taxonomy\TaxonomyTerm t
        WHERE inv.product = p.pid AND p.type=t.tid ";
      // Filters.
       $varsArray = array();
      if ( isset($query['sku']) && !empty($query['sku'])) {
        $phql .= " AND p.sku LIKE :sku: ";
        $varsArray['sku'] = '%' . $query['sku'] . '%';
      }
      if ( isset($query['name']) && !empty($query['name'])) {
        $phql .= " AND p.name LIKE :name: ";
        $varsArray['name'] = '%' . $query['name'] . '%';
      }
      
     
      $products = $app->modelsManager->executeQuery($phql, $varsArray);
      // Create a Model paginator, show 10 rows by page starting from $currentPage
      $paginator = new \Phalcon\Paginator\Adapter\Model(
          array(
              "data" => $products,
              "limit"=> 5,
              "page" => $query['page']
          )
      );
      // Get the paginated results
      $page = $paginator->getPaginate();
      //
      $list = array();
      //foreach ($products as $product) {
      foreach ($page->items as $product) {
        // Add some complex fields.
        if($product->units > 0){
            $phql = "SELECT  t.tid ,t.name FROM Triton\Models\Taxonomy\TaxonomyTerm t  WHERE t.tid = :tid:";
            $item = $app->modelsManager->executeQuery($phql, array("tid"=>$product->units))->getFirst();
            $product->unitname = $item->name; 
        }
        // Add it to the result.
        $list[] = $product;
      }
      // Prepare the return object.
      $data = array(
        "status" => "OK",
        "pager" => $page,
        "list" => $list,
      );
      // R.
      return $data;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
  
   /**
   * Returns the product details.
   * 
   * @param object $app The Phalcon Micro application.
   * @param array $query Array of filters.
   * @return object The product detail.
   */
  public static function getProduct($app, $pid) {
    try{
      // Query.
      $phql = "SELECT 
          pid
          ,sku
          ,p.name
          ,type 
          ,t.name as typename
          ,units
          ,weight
          ,size
          ,price_1
          ,price_2
          ,price_3
          ,price_4
          ,tx.name as unitname
          ,inv.amount as amount
          ";
      // Join with inventory if that the request.
      $phql .= "
        FROM Triton\Models\Inventory\Product p, 
        Triton\Models\Inventory\Inventory inv,
        Triton\Models\Taxonomy\TaxonomyTerm t,
        Triton\Models\Taxonomy\TaxonomyTerm tx
        WHERE inv.product = p.pid AND p.type=t.tid AND p.pid=:pid: AND tx.tid=p.units";
      // Filters.
       $varsArray = array("pid"=>$pid);
      
      
     
      $product = $app->modelsManager->executeQuery($phql, $varsArray)->getFirst();

      return $product;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
  
}

?>
