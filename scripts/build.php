<?php

/**
 * Data Build Script for GlobalCountries (Premium Edition)
 * This script merges multiple sources to create the most exhaustive country dataset.
 */

$dr5hnUrl = 'https://raw.githubusercontent.com/dr5hn/countries-states-cities-database/master/json/countries.json';
$mledozeUrl = 'https://raw.githubusercontent.com/mledoze/countries/master/countries.json';
$outputDir = __DIR__ . '/../data/compiled';

if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

echo "Fetching data from multiple sources...\n";

echo "1. Fetching core metadata from dr5hn...\n";
$dr5hnJson = file_get_contents($dr5hnUrl);
$dr5hnData = json_decode($dr5hnJson, true);

echo "2. Fetching visual assets from mledoze...\n";
$mledozeJson = file_get_contents($mledozeUrl);
$mledozeData = json_decode($mledozeJson, true);

$mledozeMap = [];
foreach ($mledozeData as $m) {
    $mledozeMap[$m['cca2']] = $m;
}

echo "Processing and merging datasets...\n";

$compiled = [];

foreach ($dr5hnData as $data) {
    $iso2 = $data['iso2'];
    $m = $mledozeMap[$iso2] ?? [];

    // Exhaustive Normalization
    $entry = [
        'name' => [
            'common' => $data['name'] ?? '',
            'official' => $m['name']['official'] ?? ($data['name'] ?? ''),
            'native' => $m['name']['nativeName'] ?? [],
        ],
        'iso' => [
            'alpha2' => $data['iso2'] ?? '',
            'alpha3' => $data['iso3'] ?? '',
            'numeric' => $data['numeric_code'] ?? '',
        ],
        'codes' => [
            'ioc' => $m['cioc'] ?? '',
            'fifa' => $m['fifa'] ?? '',
        ],
        'phone' => [
            'root' => '+' . ltrim($data['phonecode'] ?? '', '+'),
            'suffixes' => $m['idd']['suffixes'] ?? [],
        ],
        'geography' => [
            'continent' => $data['region'] ?? '',
            'subregion' => $data['subregion'] ?? '',
            'region' => $data['region'] ?? '',
            'latlng' => [$data['latitude'], $data['longitude']],
            'area' => (float) ($data['area_sq_km'] ?? ($m['area'] ?? 0)),
            'landlocked' => $m['landlocked'] ?? false,
            'borders' => $m['borders'] ?? [],
        ],
        'political' => [
            'capital' => $data['capital'] ?? '',
            'capital_latlng' => $m['capitalInfo']['latlng'] ?? [],
            'status' => $m['status'] ?? 'officially-assigned',
            'un_member' => $m['unMember'] ?? false,
            'independent' => $m['independent'] ?? true,
        ],
        'visual' => [
            'flag_emoji' => $data['emoji'] ?? '',
            'flag_svg' => $m['flags']['svg'] ?? "https://flagcdn.com/" . strtolower($iso2) . ".svg",
            'flag_png' => $m['flags']['png'] ?? "https://flagcdn.com/w320/" . strtolower($iso2) . ".png",
            'coat_of_arms' => $m['coatOfArms']['svg'] ?? ($m['coatOfArms']['png'] ?? ''),
        ],
        'economics' => [
            'currencies' => $m['currencies'] ?? [
                $data['currency'] => [
                    'name' => $data['currency_name'],
                    'symbol' => $data['currency_symbol']
                ]
            ],
            'gini' => $m['gini'] ?? null,
            'gdp' => $data['gdp'] ?? null,
        ],
        'localization' => [
            'languages' => $m['languages'] ?? [],
            'translations' => $m['translations'] ?? ($data['translations'] ?? []),
        ],
        'technical' => [
            'tld' => $m['tld'] ?? (array)($data['tld'] ?? []),
            'car_side' => $m['car']['side'] ?? 'right',
            'timezones' => $data['timezones'] ?? [],
            'start_of_week' => $m['startOfWeek'] ?? 'monday',
            'postal_code' => [
                'format' => $data['postal_code_format'] ?? '',
                'regex' => $data['postal_code_regex'] ?? '',
            ],
        ],
        'demographics' => [
            'population' => (int) ($data['population'] ?? 0),
            'demonyms' => $m['demonyms'] ?? [],
        ],
    ];

    $compiled[$iso2] = $entry;
}

// Save as PHP array for maximum performance
$phpContent = "<?php\n\n/**\n * Compiled Premium Country Data\n * Merged from dr5hn and mledoze\n * Generated at: " . date('Y-m-d H:i:s') . "\n */\n\nreturn " . var_export($compiled, true) . ";\n";

file_put_contents($outputDir . '/countries.php', $phpContent);

echo "Premium exhaustive build complete! Merged data saved to data/compiled/countries.php\n";
