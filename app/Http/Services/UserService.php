<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class UserService extends Service {

    /**
     * Call Base API with defined params on base class.
     * @param limit as integer.
     * Return json data.
     */
    public function invoke($limit = 0) {
        if ($limit < 1) {
            $limit = $this->limit;
        }

        $keyword = 'results';
        $response = Http::get($this->api, [$keyword => $limit]);

        return $response->successful() ? $response->json()[$keyword] : [];
    }
}