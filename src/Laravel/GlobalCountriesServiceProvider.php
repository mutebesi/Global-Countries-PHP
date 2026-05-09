<?php

namespace GlobalCountries\Laravel;

use GlobalCountries\Countries;
use GlobalCountries\CountryRepository;
use Illuminate\Support\ServiceProvider;
use GlobalCountries\Laravel\Components\Select;
use Illuminate\Validation\Rule;

class GlobalCountriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CountryRepository::class, function () {
            return new CountryRepository();
        });

        // Bind the facade-friendly instance
        $this->app->singleton('countries', function ($app) {
            return $app->make(CountryRepository::class);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register Blade component
        if ($this->app->runningInConsole() || $this->app->has('view')) {
            $this->loadViewComponentsAs('country', [
                Select::class,
            ]);
        }

        // Register Validation Rule macro
        if (class_exists(Rule::class)) {
            Rule::macro('country', function () {
                return new \GlobalCountries\Laravel\Rules\Country();
            });
        }
    }
}
