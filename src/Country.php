<?php

namespace GlobalCountries;

/**
 * Country DTO
 * Represents a single country with exhaustive, premium metadata.
 * Optimized for high-performance applications and type-safe development.
 */
final readonly class Country
{
    // Basic Identification
    public string $name;
    public string $officialName;
    public array $nativeNames;
    public string $iso2;
    public string $iso3;
    public string $isoNumeric;
    public string $ioc;
    public string $fifa;
    public string $slug;

    // Communication & Network
    public string $callingCode;
    public array $callingCodeSuffixes;
    public array $tlds;

    // Geography & Spatial
    public string $continent;
    public string $region;
    public string $subregion;
    public array $latlng;
    public float $area;
    public array $borders;
    public bool $isLandlocked;

    // Political & Sovereignty
    public string $capital;
    public array $capitalLatLng;
    public bool $isUnMember;
    public bool $isIndependent;
    public string $status;

    // Visual Assets & Identity
    public string $flagEmoji;
    public string $flagSvg;
    public string $flagPng;
    public string $coatOfArms;

    // Economics & Prosperity
    public array $currencies;
    public ?float $gdp;
    public ?float $gini;

    // Culture & Localization
    public array $languages;
    public array $translations;

    // Technical, Demographics & Standards
    public int $population;
    public string $drivingSide;
    public array $timezones;
    public string $startOfWeek;
    public array $postalCode;

    public function __construct(array $data)
    {
        // Basic
        $this->name = $data['name']['common'] ?? '';
        $this->officialName = $data['name']['official'] ?? '';
        $this->nativeNames = $data['name']['native'] ?? [];
        $this->iso2 = $data['iso']['alpha2'] ?? '';
        $this->iso3 = $data['iso']['alpha3'] ?? '';
        $this->isoNumeric = $data['iso']['numeric'] ?? '';
        $this->ioc = $data['codes']['ioc'] ?? '';
        $this->fifa = $data['codes']['fifa'] ?? '';
        $this->slug = strtolower($this->iso2);

        // Phone
        $root = $data['phone']['root'] ?? '';
        $suffixes = $data['phone']['suffixes'] ?? [];
        $this->callingCode = $root . ($suffixes[0] ?? '');
        $this->callingCodeSuffixes = $suffixes;
        $this->tlds = $data['technical']['tld'] ?? [];

        // Geography
        $this->continent = $data['geography']['continent'] ?? '';
        $this->region = $data['geography']['region'] ?? '';
        $this->subregion = $data['geography']['subregion'] ?? '';
        $this->latlng = $data['geography']['latlng'] ?? [0, 0];
        $this->area = (float) ($data['geography']['area'] ?? 0);
        $this->borders = $data['geography']['borders'] ?? [];
        $this->isLandlocked = (bool) ($data['geography']['landlocked'] ?? false);

        // Political
        $this->capital = $data['political']['capital'] ?? '';
        $this->capitalLatLng = $data['political']['capital_latlng'] ?? [];
        $this->isUnMember = (bool) ($data['political']['un_member'] ?? false);
        $this->isIndependent = (bool) ($data['political']['independent'] ?? false);
        $this->status = $data['political']['status'] ?? '';

        // Visuals
        $this->flagEmoji = $data['visual']['flag_emoji'] ?? '';
        $this->flagSvg = $data['visual']['flag_svg'] ?? '';
        $this->flagPng = $data['visual']['flag_png'] ?? '';
        $this->coatOfArms = $data['visual']['coat_of_arms'] ?? '';

        // Economics
        $this->currencies = $data['economics']['currencies'] ?? [];
        $this->gdp = isset($data['economics']['gdp']) ? (float) $data['economics']['gdp'] : null;
        $this->gini = isset($data['economics']['gini']) ? (float) (is_array($data['economics']['gini']) ? array_values($data['economics']['gini'])[0] : $data['economics']['gini']) : null;

        // Localization
        $this->languages = $data['localization']['languages'] ?? [];
        $this->translations = $data['localization']['translations'] ?? [];

        // Technical & Demographics
        $this->population = (int) ($data['demographics']['population'] ?? 0);
        $this->drivingSide = $data['technical']['car_side'] ?? 'right';
        $this->timezones = $data['technical']['timezones'] ?? [];
        $this->startOfWeek = $data['technical']['start_of_week'] ?? 'monday';
        $this->postalCode = $data['technical']['postal_code'] ?? [];
    }

    // --- Helpers ---

    /**
     * Get a translation of the country name.
     */
    public function translation(string $locale): string
    {
        return $this->translations[$locale]['common'] ?? ($this->translations[$locale] ?? $this->name);
    }

    /**
     * Get the primary currency code.
     */
    public function currencyCode(): string
    {
        return array_key_first($this->currencies) ?? '';
    }

    /**
     * Get the currency symbol.
     */
    public function currencySymbol(): string
    {
        $code = $this->currencyCode();
        return $this->currencies[$code]['symbol'] ?? '';
    }

    /**
     * Get the currency name.
     */
    public function currencyName(): string
    {
        $code = $this->currencyCode();
        return $this->currencies[$code]['name'] ?? '';
    }

    /**
     * Check if the country is a member of the European Union.
     */
    public function isEu(): bool
    {
        $euCodes = ['AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GR', 'HU', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL', 'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE'];
        return in_array($this->iso2, $euCodes);
    }

    /**
     * Get the postal code format pattern.
     */
    public function postalCodeFormat(): string
    {
        return $this->postalCode['format'] ?? '';
    }

    /**
     * Get the postal code validation regex.
     */
    public function postalCodeRegex(): string
    {
        return $this->postalCode['regex'] ?? '';
    }

    /**
     * Convert the country object to an exhaustive array for JSON/Frontend.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'official_name' => $this->officialName,
            'slug' => $this->slug,
            'iso' => [
                'alpha2' => $this->iso2,
                'alpha3' => $this->iso3,
                'numeric' => $this->isoNumeric,
            ],
            'geography' => [
                'continent' => $this->continent,
                'region' => $this->region,
                'subregion' => $this->subregion,
                'latlng' => $this->latlng,
                'area' => $this->area,
                'is_landlocked' => $this->isLandlocked,
            ],
            'political' => [
                'capital' => $this->capital,
                'population' => $this->population,
                'is_eu' => $this->isEu(),
                'is_un_member' => $this->isUnMember,
            ],
            'communication' => [
                'calling_code' => $this->callingCode,
                'tlds' => $this->tlds,
            ],
            'visuals' => [
                'flag' => $this->flagEmoji,
                'flag_svg' => $this->flagSvg,
                'coat_of_arms' => $this->coatOfArms,
            ],
            'economics' => [
                'currency' => [
                    'code' => $this->currencyCode(),
                    'symbol' => $this->currencySymbol(),
                    'name' => $this->currencyName(),
                ],
                'gdp' => $this->gdp,
                'gini' => $this->gini,
            ],
            'technical' => [
                'timezones' => $this->timezones,
                'driving_side' => $this->drivingSide,
                'postal_code' => $this->postalCode,
            ],
        ];
    }
}
