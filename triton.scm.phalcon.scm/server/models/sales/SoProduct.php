<?php
  namespace Triton\Models\Sales;
  
  use Phalcon\Mvc\Model;
  
  class SoProduct extends Model
  {
    /**
     * @var int(11)
     */
    public $spid;
    /**
     * @var int(11)
     */
    public $salesorder_order;
    /**
     * @var int(11)
     */
    public $product;
    /**
     * @var int(11)
     */
    public $so;
    /**
     * @var varchar(250)
     */
    public $description;
    /**
     * @var float
     */
    public $quantity;
    /**
     * @var int(11)
     */
    public $units;
    /**
     * @var float
     */
    public $package_quantity;
    /**
     * @var int(11)
     */
    public $package_units;
    /**
     * @var float
     */
    public $eachs_per_package_unit;
    /**
     * @var float
     */
    public $unit_price;
    /**
     * @var float
     */
    public $extended;
    /**
     * @var varchar(250)
     */
    public $notes;
    /**
     * @var text
     */
    public $slabs_data;
    /**
     * @var int
     */
    public $slabs_total;
  }
  