<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class XblogConfig extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'XblogConfig';
    }
}