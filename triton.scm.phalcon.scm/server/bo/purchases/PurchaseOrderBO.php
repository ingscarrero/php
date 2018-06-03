<?php
namespace Triton\Bo\Purchases;
/**
* @author Sergio
* @date 03/05/2015
* @brief RFP:Purchase Order CRUD
* Purchase Order Business Abstraction
*/
class PurchaseorderController{

	/**
	* @author Sergio
	* @date 03/05/2015
	* @brief RFP:Purchase Order CRUD
	* Search purchase orders by the given criteria
	* @param object Phalcon Micro Applicaion
	* @param object Query with the filter criteria
	* @return object The response of the query execution $app->modelsManager->executeQuery.
	*/
	public static function search($app, $query){
		try{
			$page = $query['page'];
	      	// Query.
			$phql = 
			"SELECT poid,
			    date,
			    supplier_so_number,
			    shipment_terms,
			    container_number,
			    delivery_type,
			    supplier,
			    supplier_payment_terms,
			    purchase_location,
			    ship_to_location,
			    freight_forwarder,
			    vessel,
			    air_bill_number,
			    planned_ex_factory_date,
			    ex_factory_date,
			    departure_port,
			    etd_port,
			    arrival_port,
			    eta_port,
			    discharge_port,
			    wiring_instructions,
			    printed_notes,
			    internal_notes,
			    terms,
			    exchange_rate,
			    user,
			    status,
			    approval_status,
			    approval_date,
			    total,
			    global
			FROM Triton\Models\Purchases\PurchaseOrder po";

			$phql .= " WHERE 1=1 ";

	    	if ($query['poid']) {
				$varsArray['poid'] = $query['poid'];
				$phql .= " AND poid = :poId:";
			}

			if ($query['supplier']) {
				$varsArray['supplier'] = $query['supplier'];
				$phql .= " AND supplier = :supplier:";
			}

			if ($query['date_from']) {
				$varsArray['date_from'] = $query['date_from'];
				$phql .= " AND date >= :date_from:";
			}

			if ($query['date_to']) {
				$varsArray['date_to']= $query['date_to'];
				$phql .= " AND date <= :date_to:";
			}

			if ($query['scope']) {
				$scope = $query['scope'];
				if ($scope == 'global') {
					$phql .= " AND global = 'yes'";
				} elseif ($scope == 'pending'){
					$phql .= " AND approval_status != 'approved'";
				} elseif ($scope == 'unapproved') {
					$phql .= " AND status != 'approved'";
				}
				
			}

			$phql .= " ORDER BY poid DESC";

			$purchaseOrders = $app->modelsManager->executeQuery($phql, $varsArray);
			// Create a Model paginator, show 20 rows by page starting from $page
	      	$paginator = new \Phalcon\Paginator\Adapter\Model(
	          array(
	              "data" => $purchaseOrders,
	              "limit"=> 20,
	              "page" => $page
	          )
	      	);

	      	// Get the paginated results
	      	$page = $paginator->getPaginate();

	      	$list = array();
	      	// Post processing.
		    foreach ($page->items as $po) {
		      // Date format.
		      $po->date = date('m/d/Y', strtotime($value->date));
		      // Supplier name.
		      if ( $po->supplier ) {
		      	$phql = "SELECT * FROM Triton\Models\Actor\Companies c WHERE c.company_id=:company_id:";
		        $company = $app->modelsManager->executeQuery($phql, array("company_id"=>$po->supplier))->getFirst();
		        $po->supplier_name = $company->company_name;
		        // Add it to the result.
	        	$list[] = $po;
		      }
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

	public static function details($app, $poid)
	{
		try{
			//Validations
			if ($poid) {
				throw new Exception("Error Processing Request. Must provide the Purchase Order Identifier", 1);
			}
			//Query
			$phql = 
				"SELECT poid,
				    date,
				    supplier_so_number,
				    shipment_terms,
				    container_number,
				    delivery_type,
				    supplier,
				    supplier_payment_terms,
				    purchase_location,
				    ship_to_location,
				    freight_forwarder,
				    vessel,
				    air_bill_number,
				    planned_ex_factory_date,
				    ex_factory_date,
				    departure_port,
				    etd_port,
				    arrival_port,
				    eta_port,
				    discharge_port,
				    wiring_instructions,
				    printed_notes,
				    internal_notes,
				    terms,
				    exchange_rate,
				    user,
				    status,
				    approval_status,
				    approval_date,
				    total,
				    global,
				    co.company_name as supplier_name,
				    pl.name as purchase_location_name,
				    sl.name as ship_to_location_name
				FROM Triton\Models\Purchases\PurchaseOrder po
					Triton\Models\Actor\Companies co, 
					Triton\Models\Actor\Branch pl, 
					Triton\Models\Actor\Branch sl
				WHERE po.supplier = co.company_id
					AND po.ship_to_location = sl.bid
					AND po.purchase_location = pl.bid
					AND poid = :poid:";
			$purchaseOrder = $app->modelsManager->executeQuery($phql, array('poid' => $poid))->getFirst();
			//R.
			return $purchaseOrder;
		}
		catch (\Exception $e) {
			$app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
		}
		// Default return.
		return FALSE;
	}



	/**
	* @author Sergio
	* @date 03/05/2015
	* @brief RFP:Purchase Order CRUD
	* Create a new PO at the DB with the provided PO instance.
	* @param object Phalcon Micro Application
	* @param object PurchaseOrder instance
	* @return object The response of the query execution $app->modelsManager->executeQuery.
	*/
	public static function create($app, $purchaseOrder){
		try {
			// Pre-processing
    		$purchaseOrder->date = date('Y-m-d h:i:s');
    		$purchaseOrder->exchange_rate = 1;
    		$purchaseOrder->user = 'admin';
    		$purchaseOrder->status = 'pending';
    		$purchaseOrder->approval_status = 'pending';
    		$purchaseOrder->total = 0;

    		// Query.
			$phql = "INSERT INTO Triton\Models\Purchases\PurchaseOrder
			(
			    date,
			    supplier_so_number,
			    shipment_terms,
			    container_number,
			    delivery_type,
			    supplier,
			    supplier_payment_terms,
			    purchase_location,
			    ship_to_location,
			    freight_forwarder,
			    vessel,
			    air_bill_number,
			    planned_ex_factory_date,
			    ex_factory_date,
			    departure_port,
			    etd_port,
			    arrival_port,
			    eta_port,
			    discharge_port,
			    wiring_instructions,
			    printed_notes,
			    internal_notes,
			    terms,
			    exchange_rate,
			    user,
			    status,
			    approval_status,
			    approval_date,
			    total,
			    global
			) VALUES (
			    :date:,
			    :supplier_so_number:,
			    :shipment_terms:,
			    :container_number:,
			    :delivery_type:,
			    :supplier:,
			    :supplier_payment_terms:,
			    :purchase_location:,
			    :ship_to_location:,
			    :freight_forwarder:,
			    :vessel:,
			    :air_bill_number:,
			    :planned_ex_factory_date:,
			    :ex_factory_date:,
			    :departure_port:,
			    :etd_port:,
			    :arrival_port:,
			    :eta_port:,
			    :discharge_port:,
			    :wiring_instructions:,
			    :printed_notes:,
			    :internal_notes:,
			    :terms:,
			    :exchange_rate:,
			    :user:,
			    :status:,
			    :approval_status:,
			    :approval_date:,
			    :total:,
			    :global:
			)";

			$status = $app->modelsManager->executeQuery($phql, array(
			    'date' => $purchaseOrder->date,
			    'supplier_so_number' => isset($purchaseOrder->supplier_so_number) ? $purchaseOrder->supplier_so_number : NULL,
			    'shipment_terms' => isset($purchaseOrder->shipment_terms) ? $purchaseOrder->shipment_terms : NULL,
			    'container_number' => isset($purchaseOrder->container_number) ? $purchaseOrder->container_number : NULL,
			    'delivery_type' => isset($purchaseOrder->delivery_type) ? $purchaseOrder->delivery_type : NULL,
			    'supplier' => isset($purchaseOrder->supplier) ? $purchaseOrder->supplier : NULL,
			    'supplier_payment_terms' => isset($purchaseOrder->supplier_payment_terms) ? $purchaseOrder->supplier_payment_terms : NULL,
			    'purchase_location' => isset($purchaseOrder->purchase_location) ? $purchaseOrder->purchase_location : NULL,
			    'ship_to_location' => isset($purchaseOrder->ship_to_location) ? $purchaseOrder->ship_to_location : NULL,
			    'freight_forwarder' => isset($purchaseOrder->freight_forwarder) ? $purchaseOrder->freight_forwarder : NULL,
			    'vessel' => isset($purchaseOrder->vessel) ? $purchaseOrder->vessel : NULL,
			    'air_bill_number' => isset($purchaseOrder->air_bill_number) ? $purchaseOrder->air_bill_number : NULL,
			    'planned_ex_factory_date' => isset($purchaseOrder->planned_ex_factory_date) ? $purchaseOrder->planned_ex_factory_date : NULL,
			    'ex_factory_date' => isset($purchaseOrder->ex_factory_date) ? $purchaseOrder->ex_factory_date : NULL,
			    'departure_port' => isset($purchaseOrder->departure_port) ? $purchaseOrder->departure_port : NULL,
			    'etd_port' => isset($purchaseOrder->etd_port) ? $purchaseOrder->etd_port : NULL,
			    'arrival_port' => isset($purchaseOrder->arrival_port) ? $purchaseOrder->arrival_port : NULL,
			    'eta_port' => isset($purchaseOrder->eta_port) ? $purchaseOrder->eta_port : NULL,
			    'discharge_port' => isset($purchaseOrder->discharge_port) ? $purchaseOrder->discharge_port : NULL,
			    'wiring_instructions' => isset($purchaseOrder->wiring_instructions) ? $purchaseOrder->wiring_instructions : NULL,
			    'printed_notes' => isset($purchaseOrder->printed_notes) ? $purchaseOrder->printed_notes : NULL,
			    'internal_notes' => isset($purchaseOrder->internal_notes) ? $purchaseOrder->internal_notes : NULL,
			    'terms' => isset($purchaseOrder->terms) ? $purchaseOrder->terms : NULL,
			    'exchange_rate' => isset($purchaseOrder->exchange_rate) ? $purchaseOrder->exchange_rate : NULL,
			    'user' => isset($purchaseOrder->user) ? $purchaseOrder->user : NULL,
			    'status' => isset($purchaseOrder->status) ? $purchaseOrder->status : NULL,
			    'approval_status' => isset($purchaseOrder->approval_status) ? $purchaseOrder->approval_status : NULL,
			    'approval_date' => isset($purchaseOrder->approval_date) ? $purchaseOrder->approval_date : NULL,
			    'total' => isset($purchaseOrder->total) ? $purchaseOrder->total : NULL,
			    'global' => isset($purchaseOrder->global) ? $purchaseOrder->global : NULL
			));
			//R.
			return $status;
		} catch (\Exception $e) {
			$app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
		}
		//Default return
		return FALSE;
	}

	/**
	* @author Sergio
	* @date 03/05/2015
	* @brief RFP:Purchase Order CRUD
	* Updates a PO with the provided information via PO instance.
	* @param object Phalcon Micro Application
	* @param object PurchaseOrder instance
	* @return object The response of the query execution $app->modelsManager->executeQuery.
	*/
	public static function update($app, $purchaseOrder){
		try {
			//Validations
			if ($query['poid']) {
			 	$varsArray['poid'] = $query['poid'];
			 } else {
			 	throw new Exception("Error Processing Request. Must provide the Purchase Order Identifier", 1);
			 }

			// Query.
			$phql = 
			"UPDATE Triton\Models\Purchases\PurchaseOrder SET
				date = :date:,
				supplier_so_number = :supplier_so_number:,
			    shipment_terms = :shipment_terms:,
			    container_number = :container_number:,
			    delivery_type = :delivery_type:,
			    supplier = :supplier:,
			    supplier_payment_terms = :supplier_payment_terms:,
			    purchase_location = :purchase_location:,
			    ship_to_location = :ship_to_location:,
			    freight_forwarder = :freight_forwarder:,
			    vessel = :vessel:,
			    air_bill_number = :air_bill_number:,
			    planned_ex_factory_date = :planned_ex_factory_date:,
			    ex_factory_date = :ex_factory_date:,
			    departure_port = :departure_port:,
			    etd_port = :etd_port:,
			    arrival_port = :arrival_port:,
			    eta_port = :eta_port:,
			    discharge_port = :discharge_port:,
			    wiring_instructions = :wiring_instructions:,
			    printed_notes = :printed_notes:,
			    internal_notes = :internal_notes:,
			    terms = :terms:,
			    exchange_rate = :exchange_rate:,
			    user = :user:,
			    status = :status:,
			    approval_status = :approval_status:,
			    approval_date = :approval_date:,
			    total = :total:,
			    global = :global: 
			WHERE  poid = :poid:";
			$status = $app->modelsManager->executeQuery($phql, array(
				'poid' => $purchaseOrder->poid,
				'date' => $purchaseOrder->date,
			    'supplier_so_number' => isset($purchaseOrder->supplier_so_number) ? $purchaseOrder->supplier_so_number : NULL,
			    'shipment_terms' => isset($purchaseOrder->shipment_terms) ? $purchaseOrder->shipment_terms : NULL,
			    'container_number' => isset($purchaseOrder->container_number) ? $purchaseOrder->container_number : NULL,
			    'delivery_type' => isset($purchaseOrder->delivery_type) ? $purchaseOrder->delivery_type : NULL,
			    'supplier' => isset($purchaseOrder->supplier) ? $purchaseOrder->supplier : NULL,
			    'supplier_payment_terms' => isset($purchaseOrder->supplier_payment_terms) ? $purchaseOrder->supplier_payment_terms : NULL,
			    'purchase_location' => isset($purchaseOrder->purchase_location) ? $purchaseOrder->purchase_location : NULL,
			    'ship_to_location' => isset($purchaseOrder->ship_to_location) ? $purchaseOrder->ship_to_location : NULL,
			    'freight_forwarder' => isset($purchaseOrder->freight_forwarder) ? $purchaseOrder->freight_forwarder : NULL,
			    'vessel' => isset($purchaseOrder->vessel) ? $purchaseOrder->vessel : NULL,
			    'air_bill_number' => isset($purchaseOrder->air_bill_number) ? $purchaseOrder->air_bill_number : NULL,
			    'planned_ex_factory_date' => isset($purchaseOrder->planned_ex_factory_date) ? $purchaseOrder->planned_ex_factory_date : NULL,
			    'ex_factory_date' => isset($purchaseOrder->ex_factory_date) ? $purchaseOrder->ex_factory_date : NULL,
			    'departure_port' => isset($purchaseOrder->departure_port) ? $purchaseOrder->departure_port : NULL,
			    'etd_port' => isset($purchaseOrder->etd_port) ? $purchaseOrder->etd_port : NULL,
			    'arrival_port' => isset($purchaseOrder->arrival_port) ? $purchaseOrder->arrival_port : NULL,
			    'eta_port' => isset($purchaseOrder->eta_port) ? $purchaseOrder->eta_port : NULL,
			    'discharge_port' => isset($purchaseOrder->discharge_port) ? $purchaseOrder->discharge_port : NULL,
			    'wiring_instructions' => isset($purchaseOrder->wiring_instructions) ? $purchaseOrder->wiring_instructions : NULL,
			    'printed_notes' => isset($purchaseOrder->printed_notes) ? $purchaseOrder->printed_notes : NULL,
			    'internal_notes' => isset($purchaseOrder->internal_notes) ? $purchaseOrder->internal_notes : NULL,
			    'terms' => isset($purchaseOrder->terms) ? $purchaseOrder->terms : NULL,
			    'exchange_rate' => isset($purchaseOrder->exchange_rate) ? $purchaseOrder->exchange_rate : NULL,
			    'user' => isset($purchaseOrder->user) ? $purchaseOrder->user : NULL,
			    'status' => isset($purchaseOrder->status) ? $purchaseOrder->status : NULL,
			    'approval_status' => isset($purchaseOrder->approval_status) ? $purchaseOrder->approval_status : NULL,
			    'approval_date' => isset($purchaseOrder->approval_date) ? $purchaseOrder->approval_date : NULL,
			    'total' => isset($purchaseOrder->total) ? $purchaseOrder->total : NULL,
			    'global' => isset($purchaseOrder->global) ? $purchaseOrder->global : NULL
			));
		    // R.
		    return $status;
		} catch (\Exception $e) {
			$app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
		}
		//Default return
		return FALSE;
	}

	/**
	* @author Sergio
	* @date 03/05/2015
	* @brief RFP:Purchase Order CRUD
	* Deletes a PO that matches with the provided Purchase Order Identifier.
	* @param object Phalcon Micro Application 
	* @param object Purchase Order Identifier
	* @return object The response of the query execution $app->modelsManager->executeQuery.
	*/
	public static function delete($app, $poid) {
		try{
			//Validations
			if ($poid) {
				throw new Exception("Error Processing Request. Must provide the Purchase Order Identifier", 1);
			}
			// Query.
			$phql = "DELETE FROM Triton\Models\Purchases\PurchaseOrder 
			WHERE poid=:poid:";
			$status = $app->modelsManager->executeQuery($phql, array("poid" => $poid));
			//R.
			return $status;
		}
		catch (\Exception $e) {
			$app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
		}
		// Default return.
		return FALSE;
	}

	/**
	* @author Sergio
	* @date 03/05/2015
	* @brief RFP:Purchase Order CRUD
	* Get Products for PO
	* @param object Phalcon Micro Application
	* @param object Query with the filter criteria
	* @return object The response of the query execution $app->modelsManager->executeQuery.
	*/
	public static function getProducts($app, $query){
		try {

			//Validations
			if ($query['poid']) {
			 	$varsArray['poid'] = $query['poid'];
			}
			else
			{
				throw new Exception("Error Processing Request. Must provide the Purchase Order Identifier", 1);
			}

			// Query.
			$phql =  "SELECT 
				pop.ppid, 
				pop.so, 
				p.pid, 
				p.name, 
				p.sku, 
				pop.description, 
				pop.quantity, 
				pop.units, 
				pop.unit_price, 
				pop.extended, 
				pop.slabs_data, 
				pop.slabs_total, 
				p.type 
			FROM  Triton\Models\Purchases\PoProduct pop, Triton\Models\Inventory\Product p 
			WHERE  p.pid = pop.product
				AND pop.purchase_order = :poid:";

			$purchaseOrderProducts = $app->modelsManager->executeQuery($phql, $varsArray);

			$list = array();
			// Post processing.
			foreach ($purchaseOrderProducts as $key => $prod) {
				if ($prod->slabs_data ) {
					$prod->bundles = json_decode($prod->slabs_data);
					//$prod->bundles = $prod->slabs_data;
				}
				else{
					$prod->bundles = FALSE;
				}
				$list[$key] = $prod;
		    }
		    // Prepare the return object.
			$data = array(
				"status" => "OK",
				"list" => $list,
			);
			// R.
			return $data;
			
		} catch (\Exception $e) {
			$app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
		}
		// Default return.
	    return FALSE;
	} 

	/**
	* @author Sergio
	* @date 03/05/2015
	* @brief RFP:Purchase Order CRUD
	* Add a product to a po
	* @param object Phalcon Micro Application
	* @param object Purchase Order Product Instance
	* @return object The response of the query execution $app->modelsManager->executeQuery.
	*/
	public static function addProduct($app, $purchaseOrderProduct){
		try {

			// Query.
			$phql = "INSERT INTO Triton\Models\Purchases\PoProduct 
			(
				ppid,
				purchase_order,
				product,
				so,
				description,
				quantity,
				units,
				package_quantity,
				package_units,
				eachs_per_package_unit,
				unit_price,
				extended,
				notes,
				slabs_data,
				slabs_total
			) VALUES (
				:ppid:,
				:purchase_order:,
				:product:,
				:so:,
				:description:,
				:quantity:,
				:units:,
				:package_quantity:,
				:package_units:,
				:eachs_per_package_unit:,
				:unit_price:,
				:extended:,
				:notes:,
				:slabs_data:,
				:slabs_total:
			)";

			$status = $app->modelsManager->executeQuery($phql, array(
				'ppid' => $ppid,
				'purchase_order' => isset($purchaseOrderProduct->purchase_order) ? $purchaseOrderProduct->purchase_order : NULL,
				'product' => isset($purchaseOrderProduct->product) ? $purchaseOrderProduct->product : NULL,
				'so' => isset($purchaseOrderProduct->so) ? $purchaseOrderProduct->so : NULL,
				'description' => isset($purchaseOrderProduct->description) ? $purchaseOrderProduct->description : NULL,
				'quantity' => isset($purchaseOrderProduct->quantity) ? $purchaseOrderProduct->quantity : NULL,
				'units' => isset($purchaseOrderProduct->units) ? $purchaseOrderProduct->units : NULL,
				'package_quantity' => isset($purchaseOrderProduct->package_quantity) ? $purchaseOrderProduct->package_quantity : NULL,
				'package_units' => isset($purchaseOrderProduct->package_units) ? $purchaseOrderProduct->package_units : NULL,
				'eachs_per_package_unit' => isset($purchaseOrderProduct->eachs_per_package_unit) ? $purchaseOrderProduct->eachs_per_package_unit : NULL,
				'unit_price' => isset($purchaseOrderProduct->unit_price) ? $purchaseOrderProduct->unit_price : NULL,
				'extended' => isset($purchaseOrderProduct->extended) ? $purchaseOrderProduct->extended : NULL,
				'notes' => isset($purchaseOrderProduct->notes) ? $purchaseOrderProduct->notes : NULL,
				'slabs_data' => isset($purchaseOrderProduct->slabs_data) ? json_encode($purchaseOrderProduct->slabs_data) : NULL,
				'slabs_total' => isset($purchaseOrderProduct->slabs_total) ? $purchaseOrderProduct->slabs_total : NULL
			));
			//R.
			return $status;
	    } catch (\Exception $e) {
			$app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
	    }
	    //Default return
		return FALSE;
	}

	/**
	* @author Sergio
	* @date 03/05/2015
	* @brief RFP:Purchase Order RFP
	* Update a product for a PO
	* @param object Phalcon Micro Application
	* @param object Purchase Order Product Instance
	* @return object The response of the query execution $app->modelsManager->executeQuery.
	*/
	public static function updateProduct($app, $purchaseOrderProduct){
		try {
			// Query.
			$phql = "UPDATE Triton\Models\Purchases\PoProduct SET
				purchase_order = :purchase_order:,
				product = :product:,
				so = :so:,
				description = :description:,
				quantity = :quantity:,
				units = :units:,
				package_quantity = :package_quantity:,
				package_units = :package_units:,
				eachs_per_package_unit = :eachs_per_package_unit:,
				unit_price = :unit_price:,
				extended = :extended:,
				notes = :notes:,
				slabs_data = :slabs_data:,
				slabs_total = :slabs_total:
			WHERE ppid = :ppid:";

			$status = $app->modelsManager->executeQuery($phql, array(
				'ppid' => $ppid,
				'purchase_order' => isset($purchaseOrderProduct->purchase_order) ? $purchaseOrderProduct->purchase_order : NULL,
				'product' => isset($purchaseOrderProduct->product) ? $purchaseOrderProduct->product : NULL,
				'so' => isset($purchaseOrderProduct->so) ? $purchaseOrderProduct->so : NULL,
				'description' => isset($purchaseOrderProduct->description) ? $purchaseOrderProduct->description : NULL,
				'quantity' => isset($purchaseOrderProduct->quantity) ? $purchaseOrderProduct->quantity : NULL,
				'units' => isset($purchaseOrderProduct->units) ? $purchaseOrderProduct->units : NULL,
				'package_quantity' => isset($purchaseOrderProduct->package_quantity) ? $purchaseOrderProduct->package_quantity : NULL,
				'package_units' => isset($purchaseOrderProduct->package_units) ? $purchaseOrderProduct->package_units : NULL,
				'eachs_per_package_unit' => isset($purchaseOrderProduct->eachs_per_package_unit) ? $purchaseOrderProduct->eachs_per_package_unit : NULL,
				'unit_price' => isset($purchaseOrderProduct->unit_price) ? $purchaseOrderProduct->unit_price : NULL,
				'extended' => isset($purchaseOrderProduct->extended) ? $purchaseOrderProduct->extended : NULL,
				'notes' => isset($purchaseOrderProduct->notes) ? $purchaseOrderProduct->notes : NULL,
				'slabs_data' => isset($purchaseOrderProduct->slabs_data) ? json_encode($purchaseOrderProduct->slabs_data) : NULL,
				'slabs_total' => isset($purchaseOrderProduct->slabs_total) ? $purchaseOrderProduct->slabs_total : NULL
			));
			//R.
			return $status;

		} catch (\Exception $e) {
			$app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
		}
		//Default return
		return FALSE;
	}

	/**
	* @author Sergio
	* @date 03/05/2015
	* @brief RFP:Purchase Order CRUD
	* Removes a Product for a PO
	* @param object Phalcon Micro Application
	* @param object Purchse Order Product Identifier 
	* @return object The response of the query execution $app->modelsManager->executeQuery.
	*/
	public static function deleteProduct($app, $ppid){
		try {
			//Validations
			if ($ppid) {
				throw new Exception("Error Processing Request. Must provide the Product Purchase Order Identifier", 1);
			}
			// Query.
      		$phql = "DELETE  FROM Triton\Models\Purchases\PoProduct 
      		WHERE ppid=:ppid:";
      		$status = $app->modelsManager->executeQuery($phql, array("ppid" => $ppid));
      		//R.
      		return $status;
		} catch (\Exception $e) {
			$app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
		}
		//Default return
		return FALSE;
	}

}