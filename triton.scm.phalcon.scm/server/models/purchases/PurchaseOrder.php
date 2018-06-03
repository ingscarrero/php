<?php
//  namespace Triton\Purchases\Models;
  
  use Phalcon\Mvc\Model;
  
  /**
   * The model for the Purchase Order DB Entity.
   */
  class PurchaseOrder extends Model
  {
    /**
     * @int(11)
     */
    public $poid;
    /**
     * @timestamp
     */
    public $date;
    /**
     * @varchar(50)
     */
    public $supplier_so_number;
    /**
     * @int(11)
     */
    public $shipment_terms;
    /**
     * @varchar(50)
     */
    public $container_number;
    /**
     * @int(11)
     */
    public $delivery_type;
    /**
     * @int(11)
     */
    public $supplier;
    /**
     * @int(11)
     */
    public $supplier_payment_terms;
    /**
     * @int(11)
     */
    public $purchase_location;
    /**
     * @int(11)
     */
    public $ship_to_location;
    /**
     * @int(11)
     */
    public $freight_forwarder;
    /**
     * @varchar(50)
     */
    public $vessel;
    /**
     * @varchar(50)
     */
    public $air_bill_number;
    /**
     * @timestamp
     */
    public $planned_ex_factory_date;
    /**
     * @timestamp
     */
    public $ex_factory_date;
    /**
     * @int(11)
     */
    public $departure_port;
    /**
     * @int(11)
     */
    public $etd_port;
    /**
     * @int(11)
     */
    public $arrival_port;
    /**
     * @int(11)
     */
    public $eta_port;
    /**
     * @int(11)
     */
    public $discharge_port;
    /**
     * @int(11)
     */
    public $wiring_instructions;
    /**
     * @text
     */
    public $printed_notes;
    /**
     * @text
     */
    public $internal_notes;
    /**
     * @text
     */
    public $terms;
    /**
     * @float
     */
    public $exchange_rate;
    /**
     * @int(11)
     */
    public $user;
    /**
     * @varchar(50)
     */
    public $status;
    /**
     * @varchar(50)
     */
    public $approval_status;
    /**
     * @datetime
     */
    public $approval_date;
    /**
     * @float
     */
    public $total;
    /**
     * @varchar(10)
     */
    public $global;

    public function getSource() {
        return 'purchase_order';
    }

    public function validation() {
    }

    public function initialize() {
    }

  }
  