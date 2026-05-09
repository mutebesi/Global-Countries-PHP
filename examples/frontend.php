<?php

require_once __DIR__ . '/../vendor/autoload.php';

use GlobalCountries\Countries;

/**
 * Example: Frontend & API Utilities
 * Demonstrates preparing data for React/Vue selects and JSON APIs.
 */

echo "--- Frontend & API Utilities ---\n\n";

// 1. Dropdown for React Select / Vue Multiselect
echo "Standard Dropdown Array:\n";
$dropdown = Countries::dropdown();
print_r(array_slice($dropdown, 0, 2));

// 2. Specialized Dropdown: African countries with emoji flags
echo "\nAfrican Countries for Select (with emojis):\n";
$africanSelect = Countries::whereContinent('Africa')
    ->sortBy('name')
    ->toDropdown('flagEmoji', 'iso2'); // custom labels/values

foreach (array_slice($africanSelect, 0, 5) as $item) {
    echo "Value: {$item['value']}, Label: {$item['label']}\n";
}

// 3. API Response Structure
echo "\nAPI Payload for a single country (Serialized JSON):\n";
$kenya = Countries::find('KE');
echo json_encode($kenya, JSON_PRETTY_PRINT);

// 4. Popular Countries Prioritization
echo "\n\nPopular Countries List:\n";
$popular = Countries::popular()->sortBy('name');
foreach ($popular as $country) {
    echo "{$country->flagEmoji} {$country->name}\n";
}
