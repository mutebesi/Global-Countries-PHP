<?php

namespace GlobalCountries\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \GlobalCountries\CountryRepository
 */
class Countries extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'countries';
    }
}
