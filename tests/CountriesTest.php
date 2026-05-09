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

    public function test_premium_data_is_present()
    {
        $country = Countries::find('KE');
        $this->assertGreaterThan(0, $country->population);
        $this->assertNotEmpty($country->callingCode);
        $this->assertNotEmpty($country->flagEmoji);
        $this->assertNotEmpty($country->currencyCode());
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

    public function test_premium_collection_filters()
    {
        $large = Countries::wherePopulationAbove(100000000);
        $this->assertGreaterThan(0, count($large));
        
        $usd = Countries::whereCurrency('USD');
        $this->assertGreaterThan(0, count($usd));
        
        $spanish = Countries::whereLanguage('spa');
        $this->assertGreaterThan(0, count($spanish));
    }

    public function test_can_generate_dropdown_options()
    {
        $options = Countries::dropdown();
        $this->assertIsArray($options);
        $this->assertArrayHasKey('label', $options[0]);
        $this->assertArrayHasKey('value', $options[0]);
    }

    public function test_collection_utilities()
    {
        $names = Countries::all()->pluck('name');
        $this->assertIsArray($names);
        $this->assertContains('Kenya', $names);
        
        $joined = Countries::whereContinent('Africa')->join(', ');
        $this->assertStringContainsString('Kenya', $joined);
    }

    public function test_can_check_validity()
    {
        $this->assertTrue(Countries::isValid('KE'));
        $this->assertFalse(Countries::isValid('XX'));
    }
}
