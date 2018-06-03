<?php
//  namespace Triton\Purchases\Models;
  
  use Phalcon\Mvc\Model;
  
  class ShipmentTerm extends Model{
    /**
     * @int(11)
     */
    public $stid;
    /**
     * @varchar(50)
     */
    public $name;
  }
  