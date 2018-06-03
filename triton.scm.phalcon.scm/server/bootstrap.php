<?php
  /**
   * The bootstrap of the REST Server.
   * 
   * 
   */
  use \Phalcon\Logger\Adapter\File as FileAdapter;
  use \Phalcon\Loader;
  use \Phalcon\DI\FactoryDefault;
  use \Phalcon\Db\Adapter\Pdo\Mysql;
  use \Phalcon\Mvc\Micro;
  use \Phalcon\Http\Response;
  //
  use Triton\Bo\Inventory\ProductBO;
  use Triton\Bo\Inventory\VanBO;
  use Triton\Bo\Inventory\ConsignmentBO;
  use Triton\Bo\Inventory\ReturnBO;
  use Triton\Bo\Util\TaxonomyBO;
  use Triton\Bo\Util\CommonBO;
  use Triton\Bo\Sales\SalesOrderBO;
  use Triton\Bo\Purchases\PurchaseOrderBO;
  
  //
  try{
    // Load the different components.
    // =========================================================================
    $loader = new Loader();
    $loader
      ->registerNamespaces(array(
        'Triton\Bo\Inventory' => __DIR__ . '/bo/inventory/',
        'Triton\Bo\Util' => __DIR__ . '/bo/util/',
        'Triton\Bo\Sales' => __DIR__ . '/bo/sales/',
        'Triton\Bo\Purchases' ==> __DIR__ . '/bo/purchases/',
        'Triton\Models\Inventory' => __DIR__ . '/models/inventory/',
        'Triton\Models\Geo' => __DIR__ . '/models/geo/',
        'Triton\Models\Taxonomy' => __DIR__ . '/models/taxonomy/',
        'Triton\Models\Actor' => __DIR__ . '/models/actor/',
        'Triton\Models\Sales' => __DIR__ . '/models/sales/',
      ))
      ->register();
    // Create the dependeny injectors and set up all the components.
    // =========================================================================
    $di = new FactoryDefault();
    // Set up the database service
    $di->set('db', function(){
      return new Mysql(array(
        "host" => "54.149.15.82",
        "username" => "sergio",
        "password" => "trp23%hd&port33",
        "dbname" => "triton"
      ));
    });
    // Set up the logger.
    $logger = new FileAdapter("logs/debug.log");
    $di->set('logger', $logger);
    //Create and bind the DI to the application
    // =========================================================================
    $app = new Micro($di);
    // =========================================================================
    // Inventory Routes.
    // Retrieves the details of an specific material.
    $app->get('/materials/view/{id:[0-9]+}', function($id) use ($app) {
      $material = ProductBO::getDetail($app, $id);
      //Create a response
      $response = new Response();
      if ($material == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      } 
      else {
        $response->setJsonContent(array(
          'status' => 'FOUND',
          'data' => $material
        ));
      }
      // R.
      return $response;    
    });
    // Retrieves all materials
    $app->get('/materials/list', function() use ($app) {
      // Get the parameters.
      $query = $app->request->getQuery();
      // Query DB.
      // @todo change
      $list = ProductBO::searchProducts($app, $query['page'], $query);
      //Create a response
      $response = new Response();
      if ($list == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      } 
      else {
        $response->setJsonContent($list);
      }
      // R.
      return $response;      
    });
    // Insert new material.
    $app->post('/materials/new', function() use ($app) {
      // Get the parameters.
      $material = $app->request->getJsonRawBody();
      $status = ProductBO::create($app, $material);
      //Create a response
      $response = new Response();
      //Check if the insertion was successful
      if ($status && $status->success() == true) {
        //Change the HTTP status
        $response->setStatusCode(201, "Created");
        $material->pid = $status->getModel()->pid;
      } 
      else {
        //Change the HTTP status
        $response->setStatusCode(409, "Conflict");
        //Send errors to the client
        $errors = array();
        if ($status) {
          foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
          }
        }
        error_log(json_encode($errors));
        $material->errors = $errors;
      }
      // Return the updated object.
      $response->setJsonContent($material);
      return $response;    
    });
    // Retrieves a taxonomy list.
    $app->get('/taxonomy/vocabulary/{vocabulary:[a-z]+}', function($vocabulary) use ($app) {
      $list = TaxonomyBO::getVocabulary($app, $vocabulary);
      //$list = array("hola amigos" => "como estas", "this" => $vocabulary);
      //Create a response
      $response = new Response();
      if ($list == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      } 
      else {
        $response->setJsonContent(array(
          'status' => 'FOUND',
          'data' => $list
        ));
      }
      // R.
      return $response;    
    });
    // Retrieves all vans
    $app->get('/materials/van/list', function() use ($app) {
      // Get the parameters.
      $query = $app->request->getQuery();
      // Query DB.
      // @todo change
      $list = VanBO::searchVans($app, $query['page'], $query);
      //Create a response
      $response = new Response();
      if ($list == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      } 
      else {
        $response->setJsonContent($list);
      }
      // R.
      return $response;      
    });
    // Retrieves the details of an specific van.
    $app->get('/materials/van/view/{id:[0-9]+}', function($id) use ($app) {
      $van = VanBO::getVanDetail($app, $id);
      //Create a response
      $response = new Response();
      if ($van == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      } 
      else {
        $response->setJsonContent(array(
          'status' => 'FOUND',
          'data' => $van
        ));
      }
      // R.
      return $response;    
    });
    // Retrieves the details of an specific van.
    $app->post('/materials/van/addProduct/{id:[0-9]+}', function($id) use ($app) {
      // Get the parameters.
      $material = $app->request->getJsonRawBody();
      $status = VanBO::addProduct($app, $id, $material);
      //Create a response
      $response = new Response();
      //Check if the insertion was successful
      if ($status && $status->success() == true) {
        //Change the HTTP status
        $response->setStatusCode(201, "Created");
        $material->pid = $status->getModel()->pid;
      } 
      else {
        //Change the HTTP status
        $response->setStatusCode(409, "Conflict");
        //Send errors to the client
        $errors = array();
        if ($status) {
          foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
          }
        }
        error_log(json_encode($errors));
        $material->errors = $errors;
      }
      // Return the updated object.
      $response->setJsonContent($material);
      // R.
      return $response;    
    });
    // Retrieves all returns
    $app->get('/materials/returns/list', function() use ($app) {
      // Get the parameters.
      $query = $app->request->getQuery();
      // Query DB.
      // @todo change
      $list = ReturnBO::search($app, $query['page'], $query);
      //Create a response
      $response = new Response();
      if ($list == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      } 
      else {
        $response->setJsonContent($list);
      }
      // R.
      return $response;      
    });
    // Retrieves the details of an specific return.
    $app->get('/materials/returns/view/{id:[0-9]+}', function($id) use ($app) {
      $van = ReturnBO::getDetail($app, $id);
      //Create a response
      $response = new Response();
      if ($van == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      } 
      else {
        $response->setJsonContent(array(
          'status' => 'FOUND',
          'data' => $van
        ));
      }
      // R.
      return $response;    
    });
    // Retrieves all vans
    $app->get('/materials/consignments/list', function() use ($app) {
      // Get the parameters.
      $query = $app->request->getQuery();
      // Query DB.
      // @todo change
      $list = ConsignmentBO::search($app, $query['page'], $query);
      //Create a response
      $response = new Response();
      if ($list == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      } 
      else {
        $response->setJsonContent($list);
      }
      // R.
      return $response;      
    });
    // Retrieves the details of an specific consignment location.
    $app->get('/materials/consignments/view/{id:[0-9]+}', function($id) use ($app) {
      $company = ConsignmentBO::getDetail($app, $id);
      //Create a response
      $response = new Response();
      if ($company == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      } 
      else {
        $response->setJsonContent(array(
          'status' => 'FOUND',
          'data' => $company
        ));
      }
      // R.
      return $response;    
    });
    
    
    //Retrive all sales order
    $app->get('/salesorder/list', function() use ($app) {
      // Get the parameters.
      $query = $app->request->getQuery();
      $list = SalesOrderBO::getAllSalesOrder($app, $query);
      //Create a response
      $response = new Response();
      if ($list == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      }else {
        $response->setJsonContent($list);
      }
      return $response;      
    });
    
    //Retrive all branch
    $app->get('/branch/list', function() use ($app) {
      // Query DB.
      $list = CommonBO::getBranch($app);
      //Create a response
      $response = new Response();
      if ($list == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      }else {
        $response->setJsonContent($list);
      }
      return $response;      
    });
    
    //Retrive all branch
    $app->get('/branch/list', function() use ($app) {
      // Query DB.
      $list = CommonBO::getBranch($app);
      //Create a response
      $response = new Response();
      if ($list == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      }else {
        $response->setJsonContent($list);
      }
      return $response;      
    });
    
    //Retrive all units
    $app->get('/units/list', function() use ($app) {
      // Query DB.
      $list = CommonBO::getAllUnits($app);
      //Create a response
      $response = new Response();
      if ($list == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      }else {
        $response->setJsonContent($list);
      }
      return $response;      
    });
    
    //Retrive all users
    $app->get('/user/list', function() use ($app) {
      // Query DB.
      $list = CommonBO::getUsers($app);
      //Create a response
      $response = new Response();
      if ($list == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      }else {
        $response->setJsonContent($list);
      }
      return $response;      
    });
    
    //Retrive all companies
    $app->get('/company/list', function() use ($app) {
      // Query DB.
      $list = CommonBO::getCompany($app);
      //Create a response
      $response = new Response();
      if ($list == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      }else {
        $response->setJsonContent($list);
      }
      return $response;      
    });


    // Retrieves the details of an specific company.
    $app->get('/company/{id:[0-9]+}', function($id) use ($app) {
      $company = CommonBO::getCompanyDetails($app, $id);
      //Create a response
      $response = new Response();
      if ($company == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      } else {
        $response->setJsonContent($company);
      }
      return $response;    
    });
    
    // Retrieves the details of an specific salesorder.
    $app->get('/salesorder/{id:[0-9]+}', function($id) use ($app) {
      $salesOrder = SalesOrderBO::getDetail($app, $id);
      //Create a response
      $response = new Response();
      if ($salesOrder == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      } else {
        $response->setJsonContent($salesOrder);
      }
      return $response;    
    });
    
    // Delete the details of an specific salesorder product.
    $app->delete('/salesorderproduct/{spid:[0-9]+}', function($spid) use ($app) {
      $status = SalesOrderBO::deleteSalesOrderProduct($app, $spid);
      //Create a response
      $response = new Response();
      if ($status == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      } else {
        $response->setJsonContent($status);
      }
      return $response;    
    });
    
    
    // Retrieves a taxonomy list.
    $app->get('/taxonomy/list', function() use ($app) {
      $list = TaxonomyBO::getTaxonomy($app);
      //Create a response
      $response = new Response();
      if ($list == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      }else {
        $response->setJsonContent($list);
      }
      return $response;    
    });
    
     // Retrieves a product list.
    $app->get('/product/list', function() use ($app) {
      $list = ProductBO::getAllProducts($app);
      //Create a response
      $response = new Response();
      if ($list == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      }else {
        $response->setJsonContent($list);
      }
      return $response;    
    });
    
    // Retrieves a taxonomy by vaocabulary .
    $app->get('/taxonomy/byvocabulary/{id:[0-9]+}', function($id) use ($app) {
      $list = TaxonomyBO::getTaxonomyByVocabulary($app, $id);
      //Create a response
      $response = new Response();
      if ($list == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      }else {
        $response->setJsonContent($list);
      }
      return $response;    
    });
    
    
    //Retrive new salesorder number
    $app->get('/salesordernumber/create', function() use ($app) {
      // @todo change
      $saleOrderNumber = SalesOrderBO::getNextSaleOrderNumber($app);
      //Create a response
      $response = new Response();
      if ($saleOrderNumber == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      }else {
        $response->setJsonContent(array('result' => $saleOrderNumber));
      }
      return $response;      
    });
    
    // Insert new sales order.
    $app->post('/salesorder/new', function() use ($app) {
      // Get the parameters.
      $salesOrder = $app->request->getJsonRawBody();
      $status = SalesOrderBO::create($app, $salesOrder);
      //Create a response
      $response = new Response();
      //Check if the insertion was successful
      if ($status && $status->success() == true) {
        //Change the HTTP status
        $response->setStatusCode(201, "Created");
        $salesOrder->soid = $status->getModel()->soid;
      } 
      else {
        //Change the HTTP status
        $response->setStatusCode(409, "Conflict");
        //Send errors to the client
        $errors = array();
        if ($status) {
          foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
          }
        }
        error_log(json_encode($errors));
        $salesOrder->errors = $errors;
      }
      // Return the updated object.
      $response->setJsonContent($salesOrder);
      return $response;    
    });
    
    
    // Insert new sales order product
    $app->post('/salesorder/product/new', function() use ($app) {
      // Get the parameters.
      $salesOrderProduct = $app->request->getJsonRawBody();
      $status = SalesOrderBO::addSalesOrderProduct($app, $salesOrderProduct);
      //Create a response
      $response = new Response();
      //Check if the insertion was successful
      if ($status && $status->success() == true) {
        //Change the HTTP status
        $response->setStatusCode(201, "Created");
        $salesOrderProduct->spid = $status->getModel()->spid;
      } 
      else {
        //Change the HTTP status
        $response->setStatusCode(409, "Conflict");
        //Send errors to the client
        $errors = array();
        if ($status) {
          foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
          }
        }
        error_log(json_encode($errors));
        $salesOrderProduct->errors = $errors;
      }
      // Return the updated object.
      $response->setJsonContent($salesOrderProduct);
      return $response;    
    });
    
       // Insert new sales order product
    $app->post('/salesorder/product/update', function() use ($app) {
      // Get the parameters.
      $salesOrderProduct = $app->request->getJsonRawBody();
      $status = SalesOrderBO::updateSalesOrderProduct($app, $salesOrderProduct);
      //Create a response
      $response = new Response();
      //Check if the insertion was successful
      if ($status && $status->success() == true) {
        //Change the HTTP status
        $response->setStatusCode(201, "Updated");
      } 
      else {
        //Change the HTTP status
        $response->setStatusCode(409, "Conflict");
        //Send errors to the client
        $errors = array();
        if ($status) {
          foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
          }
        }
        error_log(json_encode($errors));
        $salesOrderProduct->errors = $errors;
      }
      // Return the updated object.
      $response->setJsonContent($salesOrderProduct);
      return $response;    
    });
    
    // Update the details of an specific salesorder.
    $app->post('/salesorder/update', function() use ($app) {
      // Get the parameters.
      $salesOrder = $app->request->getJsonRawBody();
      $status = SalesOrderBO::update($app, $salesOrder);
      //Create a response
      $response = new Response();
      //Check if the insertion was successful
      if ($status && $status->success() == true) {
        //Change the HTTP status
        $response->setStatusCode(201, "Created");
        $salesOrder->soid = $status->getModel()->soid;
      } 
      else {
        //Change the HTTP status
        $response->setStatusCode(409, "Conflict");
        //Send errors to the client
        $errors = array();
        if ($status) {
          foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
          }
        }
        error_log(json_encode($errors));
        $salesOrder->errors = $errors;
      }
      // Return the updated object.
      $response->setJsonContent($salesOrder);
      return $response;
    });
    
    
    // Update the status of an specific salesorder.
    $app->post('/salesorder/status/update', function() use ($app) {
      // Get the parameters.
      $salesOrder = $app->request->getJsonRawBody();
      $status = SalesOrderBO::updateStatus($app, $salesOrder);
      //Create a response
      $response = new Response();
      //Check if the insertion was successful
      if ($status && $status->success() == true) {
        //Change the HTTP status
        $response->setStatusCode(201, "Updated");
        $salesOrder->soid = $status->getModel()->soid;
      } 
      else {
        //Change the HTTP status
        $response->setStatusCode(409, "Conflict");
        //Send errors to the client
        $errors = array();
        if ($status) {
          foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
          }
        }
        error_log(json_encode($errors));
        $salesOrder->errors = $errors;
      }
      // Return the updated object.
      $response->setJsonContent($salesOrder);
      return $response;
    });
    
    // Delete the details of an specific salesorder.
    $app->delete('/salesorder/delete/{soid:[0-9]+}', function($soid) use ($app) {
      $status = SalesOrderBO::delete($app, $soid);
      //Create a response
      $response = new Response();
      //Check if the insertion was successful
      if ($status && $status->success() == true) {
        //Change the HTTP status
        $response->setStatusCode(201, "Deleted");
      } 
      else {
        //Change the HTTP status
        $response->setStatusCode(409, "Conflict");
        //Send errors to the client
        $errors = array();
        if ($status) {
          foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
          }
        }
        error_log(json_encode($errors));
        $salesOrder->errors = $errors;
      }
      $response->setJsonContent($status);
      return $response;
    });
    
    // Get the details of an specific salesorder.
    $app->get('/salesorder/{soid:[0-9]+}', function($soid) use ($app) {
      $status = SalesOrderBO::getDetail($app, $soid);
      //Create a response
      $response = new Response();
      //Check if the insertion was successful
      if ($status ) {
        //Change the HTTP status
        $response->setStatusCode(201, "View Details");
      } 
      else {
        //Change the HTTP status
        $response->setStatusCode(409, "Conflict");
        //Send errors to the client
        $errors = array();
        if ($status) {
          foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
          }
        }
        error_log(json_encode($errors));
        
      }
      $response->setJsonContent($status);
      return $response;
    });
    
    
    // Search all product 
    $app->get('/product/search/list', function() use ($app) {
      // Get the parameters.
      $query = $app->request->getQuery();
      // Query DB.
      // @todo change
      $list = SalesOrderBO::searchProducts($app, $query);
      //Create a response
      $response = new Response();
      if ($list == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      } 
      else {
        $response->setJsonContent($list);
      }
      // R.
      return $response;      
    });
    
     // Fetch all product for so
    $app->get('/salesorder/product/list', function() use ($app) {
      $query = $app->request->getQuery();
      $list = SalesOrderBO::getSalesOrderProduct($app, $query);
      $response = new Response();
      if ($list == false) {
        $response->setJsonContent(array());
      }else {
        $response->setJsonContent($list);
      }
      return $response;      
    });
    
    // Get the details of an specific product.
    $app->get('/product/{pid:[0-9]+}', function($pid) use ($app) {
      $status = SalesOrderBO::getProduct($app, $pid);
      //Create a response
      $response = new Response();
      //Check if the insertion was successful
      if ($status ) {
        //Change the HTTP status
        $response->setStatusCode(201, "View Details");
      } 
      else {
        //Change the HTTP status
        $response->setStatusCode(409, "Conflict");
        //Send errors to the client
        $errors = array();
        if ($status) {
          foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
          }
        }
        error_log(json_encode($errors));
        
      }
      $response->setJsonContent($status);
      return $response;
    });

    // PURCHASE ORDER
    // =========================================================================

    // --------------------------
    // Author: Sergio
    // Date: 03/05/2015
    // RFP: Purchase Order CRUD
    // Brief: Retrieves the details of an specific purchase order
    // --------------------------
    // --> Begin
    $app->get('/purchaseorder/{poid:[0-9]+}', function($poid) use ($app) {
      $purchaseOrder = PurchaseOrderBO::details($app, $poid);
      //Create a response
      $response = new Response();
      if ($purchaseOrder == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      } else {
        $response->setJsonContent($purchaseOrder);
      }
      return $response;    
    });    
    // <-- End

    // --------------------------
    // Author: Sergio
    // Date: 03/05/2015
    // RFP: PurchaseOrder CRUD
    // Brief: Fetch all purchase orders
    // --------------------------
    // --> Begin
    $app->get('/purchaseorder/list', function() use ($app) {
      $query = $app->request->getQuery();
      $list = PurchaseOrderBO::search($app, $query);
      $response = new Response();
      if ($list == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
      } else {
        $response->setJsonContent($list);
      }
      return $response;      
    });

    // --------------------------
    // Author: Sergio
    // Date: 03/05/2015
    // RFP: Purchase Order CRUD
    // Brief: Insert new PO
    // --------------------------
    // --> Begin
    $app->post('/purchaseorder/new', function() use ($app) {
      // Get the parameters.
      $purchaseOrder = $app->request->getJsonRawBody();
      $status = PurchaseOrderBO::create($app, $purchaseOrder);
      //Create a response
      $response = new Response();
      //Check if the insertion was successful
      if ($status && $status->success() == true) {
        //Change the HTTP status
        $response->setStatusCode(201, "Created");
        $purchaseOrder->poid = $status->getModel()->poid;
      } 
      else {
        //Change the HTTP status
        $response->setStatusCode(409, "Conflict");
        //Send errors to the client
        $errors = array();
        if ($status) {
          foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
          }
        }
        error_log(json_encode($errors));
        $purchaseOrder->errors = $errors;
      }
      // Return the updated object.
      $response->setJsonContent($purchaseOrder);
      return $response;    
    });    
    // <-- End

    // --------------------------
    // Author: Sergio
    // Date: 03/05/2015
    // RFP: Purchase Order CRUD
    // Brief: Updates a PO with the provided information.
    // --------------------------
    // --> Begin
    $app->post('/purchaseorder/update', function() use ($app) {
      // Get the parameters.
      $purchaseOrder = $app->request->getJsonRawBody();
      $status = PurchaseOrderBO::update($app, $purchaseOrder);
      //Create a response
      $response = new Response();
      //Check if the insertion was successful
      if ($status && $status->success() == true) {
        //Change the HTTP status
        $response->setStatusCode(201, "Updated");
        $purchaseOrder->poid = $status->getModel()->poid;
      } 
      else {
        //Change the HTTP status
        $response->setStatusCode(409, "Conflict");
        //Send errors to the client
        $errors = array();
        if ($status) {
          foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
          }
        }
        error_log(json_encode($errors));
        $purchaseOrder->errors = $errors;
      }
      // Return the updated object.
      $response->setJsonContent($purchaseOrder);
      return $response;
    });    
    // <-- End

    // --------------------------
    // Author: Sergio
    // Date: 03/05/2015
    // RFP: Purchase Order CRUD
    // Brief: Deletes a Purchase Order that matches the provided Purchase Order Identifier.
    // --------------------------
    // --> Begin
    $app->delete('/purchaseorder/delete/{poid:[0-9]+}', function($poid) use ($app) {
      $status = PurchaseOrderBO::delete($app, $poid);
      //Create a response
      $response = new Response();
      //Check if the insertion was successful
      if ($status && $status->success() == true) {
        //Change the HTTP status
        $response->setStatusCode(201, "Deleted");
      } 
      else {
        //Change the HTTP status
        $response->setStatusCode(409, "Conflict");
        //Send errors to the client
        $errors = array();
        if ($status) {
          foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
          }
        }
        error_log(json_encode($errors));
        $status->errors = $errors;
      }
      $response->setJsonContent($status);
      return $response;
    });    
    // <-- End
    
    // Handler.
    // =========================================================================
    $app->handle();
  }
  catch (\Exception  $e) {
    print $e->getMessage();
    exit;
  }
