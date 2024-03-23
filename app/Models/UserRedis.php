<?php

namespace App\Models;

use App\Constants\AppConstant;
use Illuminate\Support\Facades\Redis;

class UserRedis {
    private $parentKey = 'hourly_record';
    private $maleKey = AppConstant::MALE;
    private $femaleKey = AppConstant::FEMALE;

    public function get() {
        $male = Redis::hget($this->parentKey, $this->maleKey);
        $female = Redis::hget($this->parentKey, $this->femaleKey);

        return [
            $male === false ? 0 : $male, 
            $female === false ? 0 : $female,
        ];
    }

    public function store($args = []) {
        Redis::hincrby($this->parentKey, $this->maleKey, $args[$this->maleKey]);
        Redis::hincrby($this->parentKey, $this->femaleKey, $args[$this->femaleKey]);
    }

    public function clear() {
        Redis::del("{$this->parentKey}");
    }
}