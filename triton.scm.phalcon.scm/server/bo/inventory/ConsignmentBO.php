<?php
namespace Triton\Bo\Inventory;

/**
 * Business logic for the consignments in the inventory catalog.
 *
 * @author juangalf
 */
class ConsignmentBO {
  
  /**
   * Returns the list of consignments.
   * 
   * @param object $app The Phalcon Micro application.
   * @param array $page int The page to show.
   * @param array $query Array of filters.
   * @return object The product detail.
   */
  public static function search($app, $page, $query) {
    try{
      // Query.
      $phql = "SELECT c.consignment_entity_id
          , co.company_id
          , co.company_name
          , co.address
          , co.city
          , co.state
          , co.phones
        FROM Triton\Models\Inventory\InventoryConsignment c
          , Triton\Models\Actor\Companies co
        WHERE c.consignment_entity_type = 'company'
          AND co.company_id = c.consignment_entity_id
        GROUP BY c.consignment_entity_id
          ,co.company_id
          , co.company_name
          , co.address
          , co.city
          , co.state
          , co.phones
      ";
      $cos = $app->modelsManager->executeQuery($phql, array());
      // Create a Model paginator, show 10 rows by page starting from $currentPage
      $paginator = new \Phalcon\Paginator\Adapter\Model(
          array(
              "data" => $cos,
              "limit"=> 20,
              "page" => $page
          )
      );
      // Get the paginated results
      $page = $paginator->getPaginate();
      //
      $list = array();
      //foreach ($products as $product) {
      foreach ($page->items as $co) {
        $co['id'] = $co['company_id'];
        // Add it to the result.
        $list[] = $co;
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
      // General Company Info.
      $phql = "SELECT co.company_id
          , co.company_name
          , co.address
          , co.city
          , co.state
          , co.phones
        FROM Triton\Models\Actor\Companies co
        WHERE co.company_id = :id:
      ";
      $company = $app->modelsManager->executeQuery($phql, array(
          'id' => $id
      ))->getFirst();
      // The inventory Query.
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
        WHERE c.consignment_entity_type = 'company'
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
      $company['inventory'] = $list;
      // R.
      return $company;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
  
}

?>
