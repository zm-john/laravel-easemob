<?php

namespace Quhang\LaravelEasemob\Resources;

use Illuminate\Support\Arr;

class Token extends Resource
{
    /**
     * 获取token，不会魂缓存数据
     * @return array
     */
    public function get()
    {
        $config = $this->getConfig();

        $body = json_encode([
            "grant_type" => "client_credentials",
            "client_id" => Arr::get($config, 'client_id'),
            "client_secret" => Arr::get($config, ('client_secret'))
        ]);

        return $this->request('post', '/token', ['body' => $body], false);
    }
}
