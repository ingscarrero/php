<?php

// namespace.

namespace Triton\Models\Sales;

use Phalcon\Mvc\Model;
use Triton\Models\Actor;
use Triton\Models\Geo;
use Triton\Models\Taxonomy;

class SalesOrder extends Model {

    /**
     * @var int(11)
     */
    public $soid;

    /**
     * @var varchar(50)
     */
    public $sales_order_number;

    /**
     * @datetime
     */
    public $date;

    /**
     * @var varchar(50)
     */
    public $customer_po_number;

    /**
     * @var varchar(150)
     */
    public $billing_attention;

    /**
     * @var varchar(150)
     */
    public $billing_address;

    /**
     * @var varchar(150)
     */
    public $billing_unit_number;

    /**
     * @var varchar(150)
     */
    public $billing_city;

    /**
     * @var varchar(150)
     */
    public $billing_state;

    /**
     * @var varchar(50)
     */
    public $billing_zip;

    /**
     * @var varchar(150)
     */
    public $billing_country;

    /**
     * @var varchar(50)
     */
    public $billing_phone;

    /**
     * @var varchar(50)
     */
    public $billing_fax;

    /**
     * @var varchar(50)
     */
    public $billing_mobile;

    /**
     * @var varchar(80)
     */
    public $billing_email;

    /**
     * @var varchar(50)
     */
    public $primary_sales_person;

    /**
     * @enum
     */
    public $ship_to;

    /**
     * @datetime
     */
    public $pickup_date;

    /**
     * @var varchar(150)
     */
    public $ship_to_attention;

    /**
     * @var varchar(150)
     */
    public $ship_to_name;

    /**
     * @var varchar(150)
     */
    public $ship_to_address;

    /**
     * @var varchar(150)
     */
    public $ship_to_unit_number;

    /**
     * @var varchar(150)
     */
    public $ship_to_city;

    /**
     * @var varchar(150)
     */
    public $ship_to_state;

    /**
     * @var varchar(50)
     */
    public $ship_to_zip;

    /**
     * @var varchar(150)
     */
    public $ship_to_country;

    /**
     * @var varchar(50)
     */
    public $ship_to_phone;

    /**
     * @var varchar(50)
     */
    public $ship_to_fax;

    /**
     * @var varchar(50)
     */
    public $ship_to_mobile;

    /**
     * @var varchar(80)
     */
    public $ship_to_email;

    /**
     * @var varchar(60)
     */
    public $geocode_latitude;

    /**
     * @var varchar(60)
     */
    public $geocode_longitude;

    /**
     * @var longtext
     */
    public $special_delivery_instructions;

    /**
     * @text
     */
    public $internal_notes;

    /**
     * @text
     */
    public $printed_notes;

    public function getSource() {
        return 'sales_order';
    }

    public function validation() {
        
                
        if ($this->ship_to != "Pick Up" && strlen($this->ship_to_company) == 0 ) {
            $this->appendMessage(new Message("Ship to Comany is required."));
        }

        //Check if any messages have been produced
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    public function initialize() {
        $this->belongsTo("location", "Branch", "bid");
        $this->belongsTo("billing_customer", "Companies", "company_id");
        $this->belongsTo("payment_terms", "TaxonomyTerm", "tid");
        $this->belongsTo("price_level", "TaxonomyTerm", "tid");
        $this->belongsTo("sales_tax", "TaxonomyTerm", "tid");
        $this->belongsTo("ship_to_company", "Companies", "company_id");
        $this->belongsTo("referred_by", "Companies", "company_id");
    }

}
