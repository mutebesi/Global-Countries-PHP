<?php

namespace GlobalCountries\Laravel\Components;

use GlobalCountries\Countries;
use Illuminate\View\Component;

class Select extends Component
{
    public string $name;
    public ?string $selected;
    public string $placeholder;
    public array $options;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $name = 'country',
        ?string $selected = null,
        string $placeholder = 'Select a country',
        ?string $continent = null
    ) {
        $this->name = $name;
        $this->selected = $selected;
        $this->placeholder = $placeholder;

        $countries = Countries::all();
        
        if ($continent) {
            $countries = $countries->whereContinent($continent);
        }

        $this->options = $countries->sortBy('name')->toDropdown();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return <<<'blade'
            <select name="{{ $name }}" {{ $attributes }}>
                @if($placeholder)
                    <option value="">{{ $placeholder }}</option>
                @endif
                @foreach($options as $option)
                    <option value="{{ $option['value'] }}" @selected($selected === $option['value'])>
                        {{ $option['label'] }}
                    </option>
                @endforeach
            </select>
        blade;
    }
}
