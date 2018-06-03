<?php
//  namespace Triton\Purchases\Models;
  
  use Phalcon\Mvc\Model;
  
  class FreightForwarder extends Model{
    /**
     * @int(11)
     */
    public $ffid;
    /**
     * @varchar(50)
     */
    public $name;
  }
  