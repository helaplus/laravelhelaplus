<?php

namespace Helaplus\Laravelhelaplus\Facades;

use Illuminate\Support\Facades\Facade;

class Laravelhelaplus extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravelhelaplus';
    }
}
