<?php

namespace CampaigningBureau\CriticalLaravelRoutes\Facades;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'critical-laravel-routes';
    }
}