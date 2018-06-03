<?php
//  namespace Triton\Purchases\Models;
  
  use Phalcon\Mvc\Model;
  
  class Units extends Model{
    /**
     * @int(11)
     */
    public $uid;
    /**
     * @varchar(50)
     */
    public $name;
  }
  