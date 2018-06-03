<?php
//  namespace Triton\Purchases\Models;
  
  use Phalcon\Mvc\Model;
  
  class AutocompleteGroup extends Model
  {
    /**
     * @var int(11)
     */
    public $gid;
    /**
     * @var varchar(50)
     */
    public $name;
    /**
     * @var varchar(100)
     */
    public $table;
    /**
     * @var varchar(100)
     */
    public $field;
    /**
     * @var varchar(1000)
     */
    public $other_fields;
  }
  