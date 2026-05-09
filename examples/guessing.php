<?php

require_once __DIR__ . '/../vendor/autoload.php';

use GlobalCountries\Countries;

/**
 * Example: Phone and Locale Guessing
 * Demonstrates intelligence features for identifying countries from inputs.
 */

echo "--- Phone & Locale Guessing ---\n\n";

// 1. Guess from Phone Number
$numbers = [
    '+254712345678', // Kenya
    '+442071234567', // UK
    '+14155552671',  // USA/Canada
    '33123456789',   // France (no +)
];

foreach ($numbers as $number) {
    $country = Countries::guessFromPhone($number);
    $name = $country ? $country->name : 'Unknown';
    echo "Phone: {$number} -> Country: {$name}\n";
}

echo "\n--- Guess from Locale ---\n";

// 2. Guess from Locale string
$locales = [
    'en_KE',
    'fr-FR',
    'de_DE',
    'en-US',
    'zh_CN'
];

foreach ($locales as $locale) {
    $country = Countries::guessFromLocale($locale);
    $name = $country ? $country->name : 'Unknown';
    echo "Locale: {$locale} -> Country: {$name}\n";
}

echo "\n--- Validation Helpers ---\n";

// 3. Validation
$code = 'XX';
if (!Countries::isValid($code)) {
    echo "'{$code}' is not a valid country code.\n";
}

$code = 'KE';
if (Countries::isValid($code)) {
    echo "'{$code}' is a valid country code for " . Countries::find($code)->name . ".\n";
}
