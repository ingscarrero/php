<?php

// namespace.

namespace Triton\Models\Sales;

use Phalcon\Mvc\Model;
use Triton\Models\Actor;
use Triton\Models\Geo;
use Triton\Models\Taxonomy;

class SalesOrderNumbers extends Model {

    /**
     * @var int(11)
     */
    public $id;
    /**
     * @var varchar(50)
     */
    public $so_number;
    /**
     * @tinyint(4)
     */
    public $is_used;
    

}
