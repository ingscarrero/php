<?php
namespace Triton\Bo\Inventory;

/**
 * Business logic for the vans in the inventory catalog.
 *
 * @author juangalf
 */
class ReturnBO {
  
  /**
   * Returns the list of returns.
   * 
   * @param object $app The Phalcon Micro application.
   * @param array $page int The page to show.
   * @param array $query Array of filters.
   * @return object The product detail.
   */
  public static function search($app, $page, $query) {
    try{
      // Query.
      $phql = "SELECT 
          rid
          , name
          , date
          , invoice
          , po
          , container
          , supplier
          , location
          , amount
        FROM Triton\Models\Inventory\InventoryReturn r
      ";
      $returns = $app->modelsManager->executeQuery($phql, array());
      // Create a Model paginator, show 10 rows by page starting from $currentPage
      $paginator = new \Phalcon\Paginator\Adapter\Model(
          array(
              "data" => $returns,
              "limit"=> 10,
              "page" => $page
          )
      );
      // Get the paginated results
      $page = $paginator->getPaginate();
      //
      $list = array();
      //foreach ($products as $product) {
      foreach ($page->items as $return) {
        $return['id'] = $return['rid'];
        // Add it to the result.
        $list[] = $return;
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
   * Returns the detail of an specific return.
   * 
   * @param object $app The Phalcon Micro application.
   * @param int $id The ID of the van to query.
   * @return object The product detail.
   */
  public static function getDetail($app, $id, $page = 1) {
    try{
      // Query.
      $phql = "SELECT 
          rid
          , name
          , date
          , invoice
          , po
          , container
          , supplier
          , location
          , amount
        FROM Triton\Models\Inventory\InventoryReturn r
        WHERE rid = :id:
      ";
      $return = $app->modelsManager->executeQuery($phql, array(
          'id' => $id
      ))->getFirst();
      // The inventory.
      // Query.
      $phql = "SELECT rp.rpid
          , rp.quantity
          , rp.unit_cost
          , rp.extended
          , p.sku
          , p.name
        FROM Triton\Models\Inventory\InventoryReturnProduct rp
          , Triton\Models\Inventory\Product p
        WHERE rp.inventory_return = :id:
          AND p.pid = rp.product
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
      $return['inventory'] = $list;
      // R.
      return $return;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
  
}

?>
