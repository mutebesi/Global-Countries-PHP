<?php

namespace GlobalCountries\Tests;

use GlobalCountries\Countries;
use GlobalCountries\Country;
use GlobalCountries\CountryCollection;
use PHPUnit\Framework\TestCase;

class CountriesTest extends TestCase
{
    public function test_can_get_all_countries()
    {
        $countries = Countries::all();
        $this->assertInstanceOf(CountryCollection::class, $countries);
        $this->assertGreaterThan(200, count($countries));
    }

    public function test_can_find_country_by_iso2()
    {
        $country = Countries::find('KE');
        $this->assertInstanceOf(Country::class, $country);
        $this->assertEquals('Kenya', $country->name);
        $this->assertEquals('Nairobi', $country->capital);
    }

    public function test_can_find_country_by_iso3()
    {
        $country = Countries::find('KEN');
        $this->assertInstanceOf(Country::class, $country);
        $this->assertEquals('Kenya', $country->name);
    }

    public function test_can_search_countries()
    {
        $results = Countries::search('United');
        $this->assertGreaterThan(1, count($results));
        $this->assertTrue($results->search('Kingdom')->count() > 0);
    }

    public function test_can_filter_by_continent()
    {
        $africa = Countries::whereContinent('Africa');
        $this->assertGreaterThan(0, count($africa));
        foreach ($africa as $country) {
            $this->assertEquals('Africa', $country->continent);
        }
    }

    public function test_can_generate_dropdown_options()
    {
        $options = Countries::dropdown();
        $this->assertIsArray($options);
        $this->assertArrayHasKey('label', $options[0]);
        $this->assertArrayHasKey('value', $options[0]);
    }

    public function test_can_check_validity()
    {
        $this->assertTrue(Countries::isValid('KE'));
        $this->assertFalse(Countries::isValid('XX'));
    }
}
