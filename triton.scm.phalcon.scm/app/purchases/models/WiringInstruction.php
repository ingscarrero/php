<?php
//  namespace Triton\Purchases\Models;
  
  use Phalcon\Mvc\Model;
  
  class WiringInstruction extends Model{
    /**
     * @int(11)
     */
    public $wiid;
    /**
     * @varchar(50)
     */
    public $name;
  }
  