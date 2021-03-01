<?php
namespace Bennent\Geauth\Facades;
use Illuminate\Support\Facades\Facade;

class Geauth extends Facade
{
   protected static function getFacadeAccessor()
    {
       return 'Geauth';
    }
}
