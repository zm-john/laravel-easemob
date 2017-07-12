<?php

namespace Quhang\LaravelEasemob;

use GuzzleHttp\Client;

class HttpClient extends Client
{
    public function __construct()
    {
        parent::__construct([
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);
    }
}
