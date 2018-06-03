<?php
namespace Triton\Bo\Util;

/**
 * Business logic for the products in the inventory catalog.
 *
 * @author juangalf
 */
class TaxonomyBO {
  
  /**
   * Returns the list of terms in a vocabulary.
   * 
   * @param object $app The Phalcon Micro application.
   * @param string $vocabulary The name of the vocabulary.
   * @return object The product detail.
   */
  public static function getVocabulary($app, $vocabulary) {
    try{
      // Query.
      $phql = "
        SELECT v.vid, v.name as vocabulary_name, v.description, t.tid, t.tid as value, t.name as label, t.name
        FROM Triton\Models\Taxonomy\TaxonomyVocabulary v
          , Triton\Models\Taxonomy\TaxonomyTerm t
        WHERE v.name = :vocabulary:
          AND t.vid = v.vid
      ";
      $terms = $app->modelsManager->executeQuery($phql, array("vocabulary" => $vocabulary));
      $list = array();
      foreach ($terms as $term) {
        // Add it to the result.
        $list[] = $term;
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
   * Returns the list of terms in a vocabulary.
   * 
   * @param object $app The Phalcon Micro application.
   * @param string $vocabulary The name of the vocabulary.
   * @return list of taxonomy.
   */
  public static function getTaxonomyByVocabulary($app, $id) {
    try{
      // Query.
      $phql = "
        SELECT  t.tid as value,  t.name as label
        FROM Triton\Models\Taxonomy\TaxonomyTerm t
        WHERE t.vid = :vid:";
      $terms = $app->modelsManager->executeQuery($phql, array("vid" => $id));
      $list = array();
      foreach ($terms as $term) {
        // Add it to the result.
        $list[] = $term;
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
   * Returns the list of terms.
   * 
   * @param object $app The Phalcon Micro application.
   * @return list of taxonoxy terms.
   */
  public static function getTaxonomy($app) {
    try{
      // Query.
      $phql = "
        SELECT t.tid as value, t.name as label
        FROM Triton\Models\Taxonomy\TaxonomyTerm t ORDER BY t.name";
      $terms = $app->modelsManager->executeQuery($phql, array());
      $list = array();
      foreach ($terms as $term) {
        // Add it to the result.
        $list[] = $term;
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
