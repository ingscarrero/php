<?php
namespace Triton\Bo\Util;

/**
 * Business logic for the products in the inventory catalog.
 *
 * @author juangalf
 */
class BranchBO {
  
  /**
   * Returns the list of branch.
   * 
   * @param object $app The Phalcon Micro application.
   * @return list of branch detail.
   */
  public static function getBranch($app) {
    try{
      // Query.
      $phql = "SELECT b.name as label, b.bid as value  FROM Triton\Models\Actor\Branch b";
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
}

?>
