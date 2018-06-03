<?php
//  namespace Triton\Purchases\Models;
  
  use Phalcon\Mvc\Model;
  
  class PoInvoice extends Model
  {
    public $poiid;
    public $purchase_order;
    public $description;
    public $date;
    public $number;
    public $total;
    public $eta_date;
    public $received_date;
    public $container_number;
    public $status;
  }
  