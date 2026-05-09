<?php

require_once __DIR__ . '/../vendor/autoload.php';

use GlobalCountries\Countries;

/**
 * Example: UI Dropdowns & Inputs
 * Demonstrates the most practical use case: generating HTML country selectors.
 */

echo "--- Practical UI Examples ---\n\n";

// 1. Standard HTML Select with Names
echo "1. Simple HTML Select:\n";
$countries = Countries::all()->sortBy('name');
echo '<select name="country">' . "\n";
foreach ($countries as $country) {
    echo "    <option value=\"{$country->iso2}\">{$country->name}</option>\n";
}
echo "</select>\n\n";

// 2. Select with Emoji Flags (Very Popular)
echo "2. Select with Emoji Flags:\n";
$popular = Countries::popular()->sortBy('name');
echo '<select name="shipping_country" class="form-select">' . "\n";
echo '    <option value="">Select a country...</option>' . "\n";
foreach ($popular as $country) {
    echo "    <option value=\"{$country->iso2}\">{$country->flagEmoji} {$country->name}</option>\n";
}
echo "    <option disabled>----------</option>\n";
foreach ($countries as $country) {
    echo "    <option value=\"{$country->iso2}\">{$country->flagEmoji} {$country->name}</option>\n";
}
echo "</select>\n\n";

// 3. Grouped by Continent
echo "3. Grouped by Continent (optgroup):\n";
$grouped = Countries::all()->groupBy('continent');
ksort($grouped); // Sort continents alphabetically

echo '<select name="region_select">' . "\n";
foreach ($grouped as $continent => $collection) {
    echo "    <optgroup label=\"{$continent}\">\n";
    foreach ($collection->sortBy('name') as $country) {
        echo "        <option value=\"{$country->iso2}\">{$country->name}</option>\n";
    }
    echo "    </optgroup>\n";
}
echo "</select>\n\n";

// 4. Data for modern JS Selects (Select2 / TomSelect)
echo "4. JSON for Searchable Selects (Select2 format):\n";
$select2Data = Countries::all()->sortBy('name')->all();
$formatted = array_map(fn($c) => [
    'id' => $c->iso2,
    'text' => $c->flagEmoji . ' ' . $c->name,
    'continent' => $c->continent
], $select2Data);

echo json_encode(array_slice($formatted, 0, 2), JSON_PRETTY_PRINT) . "\n";

echo "\n--- Practical Tip ---\n";
echo "In Laravel, simply use the provided Blade component:\n";
echo "<x-country-select name=\"country\" selected=\"KE\" class=\"form-control\" />\n";
