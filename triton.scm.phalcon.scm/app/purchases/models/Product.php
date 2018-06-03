<?php
//  namespace Triton\Purchases\Models;
  
  use Phalcon\Mvc\Model;
  
  class Product extends Model
  {
    /**
     * @var int(11)
     */
    public $pid;
    /**
     * @var varchar(250)
     */
    public $sku;
    /**
     * @var varchar(250)
     */
    public $name;
    /**
     * @var int(11)
     */
    public $type;
    /**
     * @var int(11)
     */
    public $category;
    /**
     * @var float
     */
    public $unit_cost;
  }
  