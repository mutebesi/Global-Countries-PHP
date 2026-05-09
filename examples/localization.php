<?php

require_once __DIR__ . '/../vendor/autoload.php';

use GlobalCountries\Countries;

/**
 * Example: Localization and Translations
 * Demonstrates how to access country names in multiple languages.
 */

echo "--- Localization & Translations ---\n\n";

// Find Germany
$germany = Countries::find('DE');

echo "English: {$germany->name}\n";
echo "Official (DE): {$germany->officialName}\n";

// Accessing specific translations
// mledoze/countries uses 3-letter ISO 639-2 codes for many translations
echo "French: " . $germany->translation('fra') . "\n";
echo "Spanish: " . $germany->translation('spa') . "\n";
echo "Italian: " . $germany->translation('ita') . "\n";
echo "Japanese: " . $germany->translation('jpn') . "\n";
echo "Russian: " . $germany->translation('rus') . "\n";
echo "Chinese: " . $germany->translation('zho') . "\n";

echo "\n--- Multi-language List Example ---\n";

// List top 5 countries with their French names
$top5 = Countries::all()->sortBy('name')->all();
for ($i = 0; $i < 5; $i++) {
    $country = $top5[$i];
    echo "{$country->name} -> " . $country->translation('fra') . "\n";
}
