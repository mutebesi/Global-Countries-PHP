<?php

require_once __DIR__ . '/../vendor/autoload.php';

use GlobalCountries\Countries;

// 1. Get all countries
$all = Countries::all();
echo "Total countries: " . count($all) . "\n";

// 2. Find a specific country
$kenya = Countries::find('KE');
if ($kenya) {
    echo "Name: {$kenya->name}\n";
    echo "Official Name: {$kenya->officialName}\n";
    echo "Capital: {$kenya->capital}\n";
    echo "Currency: {$kenya->currencyCode()} ({$kenya->currencySymbol()})\n";
    echo "Calling Code: {$kenya->callingCode}\n";
    echo "Flag: {$kenya->flagEmoji}\n";
}

// 3. Filtering by continent
$africanCountries = Countries::whereContinent('Africa');
echo "Countries in Africa: " . count($africanCountries) . "\n";

// 4. Searching
$searchResult = Countries::search('United');
echo "Countries matching 'United': " . count($searchResult) . "\n";
foreach ($searchResult as $country) {
    echo " - {$country->name} ({$country->iso2})\n";
}

// 5. Dropdown options
$dropdown = Countries::dropdown();
echo "Dropdown options sample (first 3):\n";
print_r(array_slice($dropdown, 0, 3));

// 6. JSON output
echo "Kenya to JSON:\n";
echo json_encode($kenya, JSON_PRETTY_PRINT) . "\n";
