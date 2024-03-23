<?php

namespace App\Constants;

use Illuminate\Support\Facades\Config;

class AppConstant {
    public const MALE = 'male';
    public const FEMALE = 'female';

    static function GET_BASE_URL() {
        return Config::get('api.base_api_url');
    }

    static function GET_BASE_LIMIT() {
        return Config::get('api.base_api_limit', 20);
    }
}