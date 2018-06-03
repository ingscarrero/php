<?php
//  namespace Triton\Purchases\Models;
  
  use Phalcon\Mvc\Model;
  
  class DeliveryType extends Model{
    /**
     * @int(11)
     */
    public $dtid;
    /**
     * @varchar(50)
     */
    public $name;
  }
  