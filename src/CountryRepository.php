<?php

namespace GlobalCountries;

/**
 * CountryRepository
 * Handles loading and caching of country data.
 */
class CountryRepository
{
    /** @var Country[]|null */
    protected ?array $countries = null;

    /**
     * Get all countries.
     */
    public function all(): CountryCollection
    {
        return new CountryCollection($this->loadCountries());
    }

    /**
     * Find a country by code (ISO2, ISO3, or Numeric).
     */
    public function find(string $code): ?Country
    {
        $code = strtoupper($code);
        $countries = $this->loadCountries();

        // Check ISO2 (primary key in our compiled array)
        if (isset($countries[$code])) {
            return $countries[$code];
        }

        // Search for ISO3 or Numeric
        foreach ($countries as $country) {
            if ($country->iso3 === $code || $country->isoNumeric === $code) {
                return $country;
            }
        }

        return null;
    }

    /**
     * Load countries from the compiled data file.
     *
     * @return Country[]
     */
    protected function loadCountries(): array
    {
        if ($this->countries !== null) {
            return $this->countries;
        }

        $dataPath = __DIR__ . '/../data/compiled/countries.php';

        if (!file_exists($dataPath)) {
            throw new \RuntimeException("Country data file not found. Please run 'php scripts/build.php' first.");
        }

        $rawData = require $dataPath;
        $this->countries = [];

        foreach ($rawData as $iso2 => $data) {
            $this->countries[$iso2] = new Country($data);
        }

        return $this->countries;
    }
}
