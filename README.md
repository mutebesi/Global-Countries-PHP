# 🌍 GlobalCountries (Premium Edition)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mutebesi/globalcountries.svg?style=flat-square)](https://packagist.org/packages/mutebesi/globalcountries)
[![Total Downloads](https://img.shields.io/packagist/dt/mutebesi/globalcountries.svg?style=flat-square)](https://packagist.org/packages/mutebesi/globalcountries)
[![PHP Version Compliance](https://img.shields.io/packagist/php-v/mutebesi/globalcountries.svg?style=flat-square)](https://packagist.org/packages/mutebesi/globalcountries)
[![License](https://img.shields.io/packagist/l/mutebesi/globalcountries.svg?style=flat-square)](https://packagist.org/packages/mutebesi/globalcountries)

**GlobalCountries** is the most complete, performant, and exhaustive country information package for the PHP ecosystem. It provides everything you need to build world-class global applications, featuring deeply detailed metadata for every country and territory on Earth.

---

## 📖 Table of Contents
- [✨ Features](#-features)
- [🚀 Installation](#-installation)
- [💡 Quick Start](#-quick-start)
- [🔥 Pro Tips & Advanced Usage](#-pro-tips--advanced-usage)
- [🔍 Searching & Filtering](#-searching--filtering)
- [🗺️ Exhaustive Data Model](#-exhaustive-data-model)
- [🌍 Localization](#-localization)
- [📱 Phone Number Intelligence](#-phone-number-intelligence)
- [🏗️ Laravel Integration](#-laravel-integration)
- [📊 Performance](#-performance)
- [🎨 Frontend & UI Utilities](#-frontend-&-ui-utilities)
- [🤝 Contributing](#-contributing)

---

## ✨ Features

- 🚀 **Extreme Performance**: Data is pre-compiled into optimized PHP arrays for near-instant access.
- 📦 **Exhaustive Metadata**: Deep details including population, GDP, Gini index, postal formats, and more.
- 🎨 **Visual Assets**: SVG/PNG flags, Emoji support, and Coat of Arms (SVG/PNG).
- 🌍 **Localization**: Multilingual support for country names and native scripts.
- 🔍 **Fluent API**: Powerful searching, filtering, and sorting with a "magical" interface.
- 🏗️ **Framework Agnostic**: Works perfectly in Vanilla PHP, Laravel, Symfony, and WordPress.
- 🎨 **Laravel Ready**: Includes Service Provider, Facades, Validation Rules, and Blade Components.

---

## 🚀 Installation

Clone this repository or include it in your project:
```bash
git clone https://github.com/mutebesi/Global-Countries-PHP.git
```

If using Composer:
```bash
composer require mutebesi/globalcountries
```

---

## 💡 Quick Start

```php
use GlobalCountries\Countries;

// Get all countries
$countries = Countries::all();

// Find a specific country by ISO code (Alpha-2, Alpha-3, or Numeric)
$kenya = Countries::find('KE');

echo $kenya->name;         // Kenya
echo $kenya->capital;      // Nairobi
echo $kenya->population;   // 53771300
echo $kenya->flagEmoji();  // 🇰🇪
```

---

## 🔥 Pro Tips & Advanced Usage

Make your code feel like magic with our advanced fluent API:

```php
// 1. One-liner to get all African country names joined by commas
echo Countries::whereContinent('Africa')->sortBy('name')->join(', ');

// 2. Pluck specific fields as an associative array [iso2 => callingCode]
$dialCodes = Countries::whereContinent('Europe')->pluck('callingCode', 'iso2');

// 3. Filter countries by population threshold
$giants = Countries::wherePopulationAbove(100000000); // 100M+

// 4. Guess country from phone and get its currency symbol instantly
echo Countries::guessFromPhone('+254712345678')->currencySymbol(); // KSh

// 5. Group by continent and get the count for each
foreach (Countries::all()->groupBy('continent') as $continent => $group) {
    echo "{$continent}: {$group->count()} countries";
}
```

---

## 🔍 Searching & Filtering

```php
// Filter by continent
$africa = Countries::whereContinent('Africa');

// Filter by language (ISO 639-2 or 639-1)
$spanishSpeaking = Countries::whereLanguage('spa');

// Get all EU member states
$eu = Countries::eu();

// Search by name or code (fuzzy search)
$results = Countries::search('United');

// Sorting
$sorted = Countries::all()->sortBy('population');
```

---

## 🗺️ Exhaustive Data Model

Each `Country` object is an immutable DTO containing premium metadata:

### Basic Information
- `$country->name`: Common name (e.g., "Kenya")
- `$country->officialName`: Official name (e.g., "Republic of Kenya")
- `$country->nativeNames`: Native names keyed by language code.
- `$country->iso2`, `$country->iso3`, `$country->isoNumeric`: ISO 3166-1 codes.
- `$country->ioc`, `$country->fifa`: Sports organization codes.
- `$country->slug`: URL-friendly identifier (e.g., "ke").

### Geography
- `$country->continent`, `$country->region`, `$country->subregion`.
- `$country->latlng`: `[latitude, longitude]`.
- `$country->area`: Total area in km².
- `$country->borders`: Neighboring country ISO-3 codes.
- `$country->isLandlocked`: Boolean status.

### Political & Demographics
- `$country->capital`: Capital city name.
- `$country->capitalLatLng`: Coordinates of the capital city.
- `$country->population`: Total population count.
- `$country->status`: Sovereignty status.
- `$country->isUnMember`, `$country->isIndependent`.

### Communication & Visuals
- `$country->callingCode`: International prefix (e.g., "+254").
- `$country->flagEmoji`, `$country->flagSvg`, `$country->flagPng`.
- `$country->coatOfArms`: Coat of Arms (SVG/PNG URL).

### Economics & Technical
- `$country->currencyCode()`, `$country->currencySymbol()`, `$country->currencyName()`.
- `$country->gdp`: Gross Domestic Product.
- `$country->gini`: Gini index of income inequality.
- `$country->postalCodeFormat()`, `$country->postalCodeRegex()`.
- `$country->tlds`: Internet domains.
- `$country->drivingSide`: "left" or "right".
- `$country->timezones`: Supported timezones.
- `$country->startOfWeek`: e.g., "monday".

---

## 🏗️ Laravel Integration

### Validation Rule
```php
use Illuminate\Validation\Rule;

$request->validate([
    'country_code' => ['required', Rule::country()],
]);
```

### Blade Component
```html
<x-country-select name="country" selected="KE" class="custom-select" />
```

---

## 📊 Performance

| Operation | Performance |
| --- | --- |
| **Lookup (ISO)** | < 0.001ms |
| **Filtering (All)** | ~0.02ms |
| **Data Size** | ~1.5MB (Pre-compiled PHP) |

---

## 🎨 Frontend & UI Utilities

```php
// Generate practical HTML selects with flags and grouping
foreach (Countries::all()->sortBy('name') as $country) {
    echo "<option value='{$country->iso2}'>{$country->flagEmoji} {$country->name}</option>";
}
```

---

## 🤝 Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
