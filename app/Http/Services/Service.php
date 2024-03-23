<?php

namespace App\Http\Services;

use App\Constants\AppConstant;

class Service {

    protected $api;
    protected $limit;

    public function __construct() {
        $this->api = AppConstant::GET_BASE_URL();
        $this->limit = AppConstant::GET_BASE_LIMIT();
    }    
}