<?php
//  namespace Triton\Purchases\Models;
  
  use Phalcon\Mvc\Model;
  
  class Locations extends Model
  {
    /**
     * @var varchar(3)
     */
    public $location_id;
    /**
     * @var longtext
     */
    public $subdivision_id;
    /**
     * @var varchar(2)
     */
    public $country_id;
    /**
     * @var int(11)
     */
    public $unknown;
    /**
     * @var int(11)
     */
    public $port;
    /**
     * @var int(11)
     */
    public $rail_Terminal;
    /**
     * @var int(11)
     */
    public $road_Terminal;
    /**
     * @var int(11)
     */
    public $airport;
    /**
     * @var int(11)
     */
    public $postal_exchange_office;
    /**
     * @var int(11)
     */
    public $multimodal_functions;
    /**
     * @var int(11)
     */
    public $fixed_transport_functions;
    /**
     * @var int(11)
     */
    public $inland_port;
    /**
     * @var int(11)
     */
    public $border_crossing;
    /**
     * @var varchar(3)
     */
    public $iata;
    /**
     * @var varchar(16)
     */
    public $coordinates;
    /**
     * @var longtext
     */
    public $location_name;
  }
  