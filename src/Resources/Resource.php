<?php

namespace Quhang\LaravelEasemob\Resources;

use Quhang\LaravelEasemob\Service;
use Quhang\LaravelEasemob\Exception\LaravelEasemobException;

class Resource
{
    public function getService()
    {
        return app(Service::class);
    }

    public function __call($method, $args)
    {
        $service = $this->getService();
        if (!method_exists($service, $method)) {
            throw new LaravelEasemobException("method: {$method} not exist");
        }
        return $service->{$method}(...$args);
    }
}
