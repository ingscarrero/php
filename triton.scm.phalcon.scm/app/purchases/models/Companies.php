<?php
//  namespace Triton\Purchases\Models;
  
  use Phalcon\Mvc\Model;
  
  class Companies extends Model
  {
    /**
     * @var int(11)
     */
    public $account_id;
    /**
     * @var longtext
     */
    public $address;
    /**
     * @var longtext
     */
    public $address2;
    /**
     * @var int(11)
     */
    public $anual_revenue;
    /**
     * @var longtext
     */
    public $city;
    /**
     * @var int(11)
     */
    public $company_id;
    /**
     * @var longtext
     */
    public $company_name;
    /**
     * @var longtext
     */
    public $country;
    /**
     * @var datetime
     */
    public $created;
    /**
     * @var longtext
     */
    public $created_by;
    /**
     * @var longtext
     */
    public $description;
    /**
     * @var longtext
     */
    public $email;
    /**
     * @var int(11)
     */
    public $employees;
    /**
     * @var longtext
     */
    public $ext;
    /**
     * @var longtext
     */
    public $faxes;
    /**
     * @var tinyint(4)
     */
    public $industry_id;
    /**
     * @var longtext
     */
    public $lead;
    /**
     * @var longtext
     */
    public $lead1;
    /**
     * @var datetime
     */
    public $modified;
    /**
     * @var longtext
     */
    public $modified_by;
    /**
     * @var longtext
     */
    public $phones;
    /**
     * @var longtext
     */
    public $state;
    /**
     * @var longtext
     */
    public $temp_id;
    /**
     * @var longtext
     */
    public $webpage;
    /**
     * @var longtext
     */
    public $zip_code;
  }
  