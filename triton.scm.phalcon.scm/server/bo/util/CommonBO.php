<?php
namespace Triton\Bo\Util;

/**
 * Business logic for the products in the inventory catalog.
 *
 * @author juangalf
 */
class CommonBO {
  
  /**
   * Returns the list of branch.
   * 
   * @param object $app The Phalcon Micro application.
   * @return list of branch detail.
   */
  public static function getBranch($app) {
    try{
      // Query.
      $phql = "SELECT b.name as label , b.bid as value  FROM Triton\Models\Actor\Branch b";
      $branches = $app->modelsManager->executeQuery($phql, array());
      $list = array();
      foreach ($branches as $branch) {
        // Add it to the result.
        $list[] = $branch;
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
  
  /**
   * Returns the list of branch.
   * 
   * @param object $app The Phalcon Micro application.
   * @return list of branch detail.
   */
  public static function getCompany($app) {
    try{
      // Query.
      $phql = "SELECT c.company_name as label , c.company_id as value FROM Triton\Models\Actor\Companies c";
      $items = $app->modelsManager->executeQuery($phql, array());
      $list = array();
      foreach ($items as $item) {
        // Add it to the result.
        $list[] = $item;
      }
      return $list;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
  
  /**
   * Returns the company details.
   * 
   * @param object $app The Phalcon Micro application.
   * @return company detail.
   */
  public static function getCompanyDetails($app, $company_id) {
    try{
      // Query.
      $phql = "SELECT * FROM Triton\Models\Actor\Companies c WHERE c.company_id=:company_id:";
      $item = $app->modelsManager->executeQuery($phql, array("company_id"=>$company_id))->getFirst();
      // R.
      return $item;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
  
  /**
   * Returns the list of users.
   * @param object $app The Phalcon Micro application.
   * @return list of users.
   */
  public static function getUsers($app) {
    try{
      // Query.
      $phql = "
        SELECT u.user_login as value, u.user_login as label
        FROM Triton\Models\Actor\Users u WHERE u.user_login IS NOT NULL OR u.user_login !='' ORDER BY u.user_login";
      $items = $app->modelsManager->executeQuery($phql, array());
      $list = array();
      foreach ($items as $item) {
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
  
  /**
   * Returns the list of units.
   * @param object $app The Phalcon Micro application.
   * @return list of units.
   */
  public static function getAllUnits($app) {
    try{
      // Query.
      $phql = "
        SELECT u.uid as value, u.name as label
        FROM Triton\Models\Inventory\Units u ORDER BY u.name";
      $items = $app->modelsManager->executeQuery($phql, array());
      $list = array();
      foreach ($items as $item) {
        // Add it to the result.
        $list[] = $item;
      }
      return $list;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
}

?>
