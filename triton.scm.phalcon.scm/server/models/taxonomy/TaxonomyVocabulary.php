<?php
// namespace.
namespace Triton\Models\Taxonomy;

use Phalcon\Mvc\Model;

class TaxonomyVocabulary extends Model{
  public function validation()
  {}
  /**
   * This model is mapped to the table taxonomy_term
   */
  public function getSource(){
    return 'taxonomy_vocabulary';
  }
}
  