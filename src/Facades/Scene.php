<?php

namespace Bigmom\VeEditor\Facades;

use Illuminate\Support\Facades\Facade;

class Scene extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'scene';
    }
}
