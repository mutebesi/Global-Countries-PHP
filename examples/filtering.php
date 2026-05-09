<?php

require_once __DIR__ . '/../vendor/autoload.php';

use GlobalCountries\Countries;

/**
 * Example: Filtering and Sorting
 * Demonstrates the fluent API for complex data selection.
 */

echo "--- Filtering & Sorting ---\n\n";

// 1. Fluent Chaining: Smallest countries in Europe
echo "Smallest 5 countries in Europe:\n";
$smallestEurope = Countries::whereContinent('Europe')
    ->sortBy('area');

$i = 0;
foreach ($smallestEurope as $country) {
    echo " - {$country->name} ({$country->area} km²)\n";
    if (++$i >= 5) break;
}

// 2. Regional Filtering
echo "\nNordic Countries:\n";
$nordics = Countries::whereRegion('Northern Europe')
    ->search('Denmark|Finland|Iceland|Norway|Sweden'); // search supports simple string match

foreach ($nordics as $country) {
    echo " - {$country->name} ({$country->iso2})\n";
}

// 3. Grouping by Continent
echo "\nGrouping Countries by Continent:\n";
$grouped = Countries::all()->groupBy('continent');

foreach ($grouped as $continent => $collection) {
    echo "{$continent}: " . count($collection) . " countries\n";
}

// 4. Getting EU Member States
echo "\nEU Member States (Sample of 3):\n";
$eu = Countries::eu()->sortBy('name');
$sample = array_slice($eu->all(), 0, 3);
foreach ($sample as $country) {
    echo " - {$country->name} 🇪🇺\n";
}
