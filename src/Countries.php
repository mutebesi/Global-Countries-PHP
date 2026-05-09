<?php

namespace GlobalCountries;

/**
 * Countries Facade/Proxy
 * The "magical" entry point for the GlobalCountries library.
 */
class Countries
{
    protected static ?CountryRepository $repository = null;

    /**
     * Get the repository instance.
     */
    public static function getRepository(): CountryRepository
    {
        if (static::$repository === null) {
            static::$repository = new CountryRepository();
        }

        return static::$repository;
    }

    /**
     * Set a custom repository (for testing or extension).
     */
    public static function setRepository(CountryRepository $repository): void
    {
        static::$repository = $repository;
    }

    /**
     * Get all countries.
     */
    public static function all(): CountryCollection
    {
        return static::getRepository()->all();
    }

    /**
     * Find a country by code.
     */
    public static function find(string $code): ?Country
    {
        return static::getRepository()->find($code);
    }

    /**
     * Search countries by query.
     */
    public static function search(string $query): CountryCollection
    {
        return static::all()->search($query);
    }

    /**
     * Filter by continent.
     */
    public static function whereContinent(string $continent): CountryCollection
    {
        return static::all()->whereContinent($continent);
    }

    /**
     * Get EU countries.
     */
    public static function eu(): CountryCollection
    {
        return static::all()->eu();
    }

    /**
     * Helper to get dropdown options.
     */
    public static function dropdown(string $label = 'name', string $value = 'iso2'): array
    {
        return static::all()->sortBy('name')->toDropdown($label, $value);
    }

    /**
     * Validate a country code.
     */
    public static function isValid(string $code): bool
    {
        return static::find($code) !== null;
    }

    /**
     * Guess country from a phone number.
     */
    public static function guessFromPhone(string $phone): ?Country
    {
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        $countries = static::all();
        
        // Try longest matches first
        for ($i = 5; $i >= 2; $i--) {
            $prefix = substr($phone, 0, $i);
            foreach ($countries as $country) {
                if ($country->callingCode === $prefix) {
                    return $country;
                }
            }
        }

        return null;
    }

    /**
     * Guess country from a locale (e.g. en_KE or en-US).
     */
    public static function guessFromLocale(string $locale): ?Country
    {
        $parts = preg_split('/[_-]/', $locale);
        $code = end($parts);
        
        return static::find($code);
    }

    /**
     * Forward calls to the repository/collection if needed.
     */
    public static function __callStatic(string $method, array $arguments)
    {
        // Check if method exists on collection for easy filtering
        if (method_exists(CountryCollection::class, $method)) {
            return static::all()->{$method}(...$arguments);
        }

        throw new \BadMethodCallException("Method {$method} does not exist.");
    }
}
