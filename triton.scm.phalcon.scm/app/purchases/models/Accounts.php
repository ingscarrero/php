<?php
//  namespace Triton\Purchases\Models;
  
  use Phalcon\Mvc\Model;
  
  class Accounts extends Model
  {
    public $account_id;
    public $company_id;
    public $account_name;
    public $account_owner_id;
    public $account_status_id;
    public $account_type_id;
    public $initial_contact;
    public $credit_terms;
    public $modified_by;
    public $modified;
    public $created;
    public $created_by;
    public $tax_id;
    public $temp_id;
    public $tax_rate;
    public $credit_limit;
    public $credit_status;
    public $charge_box;
    public $charge_shape;
    public $notes;
    public $sales_owner_id;
    public $sales_percent_low;
    public $sales_percent_high;
  }
  