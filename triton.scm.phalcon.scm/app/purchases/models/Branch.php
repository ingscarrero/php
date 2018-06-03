<?php
//  namespace Triton\Purchases\Models;
  
  use Phalcon\Mvc\Model;
  
  class Branch extends Model
  {
    /**
     * @var int(11)
     */
    public $bid;
    /**
     * @var varchar(50)
     */
    public $name;
    /**
     * @var varchar(250)
     */
    public $address;
    /**
     * @var varchar(250)
     */
    public $address2;
    /**
     * @var varchar(100)
     */
    public $city;
    /**
     * @var varchar(10)
     */
    public $state;
    /**
     * @var varchar(10)
     */
    public $zip_code;
    /**
     * @var varchar(10)
     */
    public $country;
  }
  