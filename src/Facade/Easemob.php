<?php

namespace Quhang\LaravelEasemob\Facade;

use Illuminate\Support\Facades\Facade;

class Easemob extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-easemob';
    }
}
