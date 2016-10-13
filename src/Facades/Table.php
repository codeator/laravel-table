<?php

namespace Codeator\Table\Facades;

use Illuminate\Support\Facades\Facade;

class Table extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'table';
    }
    
    protected static function resolveFacadeInstance($name)
    {
        return static::$app[$name];
    }
}
