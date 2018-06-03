<?php
namespace Triton\Bo\Inventory;

/**
 * Business logic for the vans in the inventory catalog.
 *
 * @author juangalf
 */
class VanBO {
  
  /**
   * Returns the list of vans.
   * 
   * @param object $app The Phalcon Micro application.
   * @param array $page int The page to show.
   * @param array $query Array of filters.
   * @return object The product detail.
   */
  public static function searchVans($app, $page, $query) {
    try{
      // Query.
      $phql = "SELECT 
          vid
          , name
          , type
          , description
          , model
          , id
          , capacity
        FROM Triton\Models\Inventory\Vehicle p
        WHERE 1=1
      ";
      $vans = $app->modelsManager->executeQuery($phql, array());
      // Create a Model paginator, show 10 rows by page starting from $currentPage
      $paginator = new \Phalcon\Paginator\Adapter\Model(
          array(
              "data" => $vans,
              "limit"=> 5,
              "page" => $page
          )
      );
      // Get the paginated results
      $page = $paginator->getPaginate();
      //
      $list = array();
      //foreach ($products as $product) {
      foreach ($page->items as $van) {
        // Add some complex fields.
        $van['id'] = $van['vid'];
        // Add it to the result.
        $list[] = $van;
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
   * Returns the detail of an specific van.
   * 
   * @param object $app The Phalcon Micro application.
   * @param int $id The ID of the van to query.
   * @return object The product detail.
   */
  public static function getVanDetail($app, $id, $page = 1) {
    try{
      // Query.
      $phql = "SELECT 
          vid
          , name
          , type
          , description
          , model
          , id
          , capacity
        FROM Triton\Models\Inventory\Vehicle p
        WHERE vid = :id:
      ";
      $van = $app->modelsManager->executeQuery($phql, array(
          'id' => $id
      ))->getFirst();
      // The invetory.
      // Query.
      $phql = "SELECT p.pid
          , p.sku
          , p.name
          , p.description
          , p.price_1
          , c.cid
          , c.amount
        FROM Triton\Models\Inventory\InventoryConsignment c
          , Triton\Models\Inventory\Inventory i
          , Triton\Models\Inventory\Product p
        WHERE c.consignment_entity_type = 'vehicle'
          AND c.consignment_entity_id = :id:
          AND i.iid = c.inventory
          AND p.pid = i.product
      ";
      $products = $app->modelsManager->executeQuery($phql, array('id' => $id));
      // Create a Model paginator
      $paginator = new \Phalcon\Paginator\Adapter\Model(
          array(
              "data" => $products,
              "limit"=> 20,
              "page" => $page
          )
      );
      // Get the paginated results
      $page = $paginator->getPaginate();
      //
      $list = array();
      //foreach ($products as $product) {
      foreach ($page->items as $product) {
        // Add it to the result.
        $list[] = $product;
      }
      $van['inventory'] = $list;
      // R.
      return $van;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
  
  /**
   * Add inventory to van.
   * 
   * @param object $app The Phalcon Micro application.
   * @param object $material The material object with all the data.
   * @return object The response of the query execution $app->modelsManager->executeQuery.
   */
  public static function addProduct($app, $van, $material){
    try{
      // Insert.
      $phql = "INSERT INTO Triton\Models\Inventory\InventoryConsignment ( 
          inventory
          , consignment_entity_type
          , consignment_entity_id
          , amount
        )
        VALUES (
          :inventory:
          , :type:
          , :van:
          , :amount:
        )
      ";
      $status = $app->modelsManager->executeQuery($phql, array(
        'inventory' => $material->iid
        ,'type' => 'vehicle'
        ,'van' => $van
        ,'amount' => $material->amount
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
}

?>
