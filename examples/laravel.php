<?php

/**
 * Example: Laravel Integration Logic
 * This file contains snippets showing how to use the package within a Laravel application.
 * Note: This script is for demonstration and won't run outside a Laravel environment.
 */

/*
// 1. Using the Facade in a Controller
namespace App\Http\Controllers;

use Countries;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('checkout', [
            'countries' => Countries::dropdown(),
            'popular' => Countries::popular()->toDropdown(),
        ]);
    }
}

// 2. Request Validation
public function store(Request $request)
{
    $request->validate([
        'billing_country' => ['required', \Illuminate\Validation\Rule::country()],
    ]);
}

// 3. Blade Component usage in tracker.blade.php
// <x-country-select name="shipping_country" selected="KE" continent="Africa" class="form-control" />

// 4. Using Guessing in a Service
$detectedCountry = Countries::guessFromPhone($request->user()->phone_number);
$currency = $detectedCountry->currencyCode();

// 5. Eloquent Attribute Casting (Conceptual)
// You can use the Country code in your DB and wrap it in a DTO
public function getCountryAttribute($value)
{
    return \GlobalCountries\Countries::find($value);
}
*/

echo "Laravel integration examples are documented within this file's comments and the README.md.\n";
