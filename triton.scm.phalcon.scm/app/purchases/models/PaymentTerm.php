<?php
//  namespace Triton\Purchases\Models;
  
  use Phalcon\Mvc\Model;
  
  class PaymentTerm extends Model{
    /**
     * @int(11)
     */
    public $ptid;
    /**
     * @varchar(50)
     */
    public $name;
  }
  