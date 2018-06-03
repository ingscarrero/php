<?php
// namespace.
namespace Triton\Models\Taxonomy;

use Phalcon\Mvc\Model;

class TaxonomyTerm extends Model{
  public function validation()
  {}
  /**
   * This model is mapped to the table taxonomy_term
   */
  public function getSource(){
    return 'taxonomy_term';
  }
  
   /**
     * @var int(11)
     */
    public $tid;
    /**
     * @var int(11)
     */
    public $vid;
    /**
     * @var varchar(250)
     */
    public $name;
    
}
  