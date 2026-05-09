<?php

namespace GlobalCountries;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * CountryCollection
 * A premium, fluent collection of Country objects.
 */
class CountryCollection implements IteratorAggregate, Countable, ArrayAccess, JsonSerializable
{
    /** @var Country[] */
    protected array $items;

    public function __construct(array $items = [])
    {
        $this->items = array_values($items);
    }

    /**
     * Filter countries by continent.
     */
    public function whereContinent(string $continent): self
    {
        return $this->filter(fn(Country $c) => strtolower($c->continent) === strtolower($continent));
    }

    /**
     * Filter countries by region.
     */
    public function whereRegion(string $region): self
    {
        return $this->filter(fn(Country $c) => strtolower($c->region) === strtolower($region));
    }

    /**
     * Filter by population.
     */
    public function wherePopulationAbove(int $amount): self
    {
        return $this->filter(fn(Country $c) => $c->population > $amount);
    }

    public function wherePopulationBelow(int $amount): self
    {
        return $this->filter(fn(Country $c) => $c->population < $amount);
    }

    /**
     * Filter by currency.
     */
    public function whereCurrency(string $code): self
    {
        $code = strtoupper($code);
        return $this->filter(fn(Country $c) => array_key_exists($code, $c->currencies));
    }

    /**
     * Filter by language.
     */
    public function whereLanguage(string $code): self
    {
        $code = strtolower($code);
        return $this->filter(fn(Country $c) => array_key_exists($code, $c->languages) || in_array($code, $c->languages));
    }

    /**
     * Search countries by name or code.
     */
    public function search(string $query): self
    {
        $query = strtolower($query);
        return $this->filter(function(Country $c) use ($query) {
            return str_contains(strtolower($c->name), $query) ||
                   str_contains(strtolower($c->officialName), $query) ||
                   strtolower($c->iso2) === $query ||
                   strtolower($c->iso3) === $query;
        });
    }

    /**
     * Get countries in the European Union.
     */
    public function eu(): self
    {
        return $this->filter(fn(Country $c) => $c->isEu());
    }

    /**
     * Get popular countries.
     */
    public function popular(): self
    {
        $popularCodes = ['US', 'GB', 'CA', 'AU', 'DE', 'FR', 'CN', 'JP', 'KE', 'NG', 'ZA'];
        return $this->filter(fn(Country $c) => in_array($c->iso2, $popularCodes));
    }

    /**
     * Group countries by a property.
     */
    public function groupBy(string $property): array
    {
        $groups = [];
        foreach ($this->items as $item) {
            $key = $item->{$property} ?? 'Unknown';
            $groups[$key][] = $item;
        }
        return array_map(fn($group) => new self($group), $groups);
    }

    /**
     * Map the collection to a new array.
     */
    public function map(callable $callback): array
    {
        return array_map($callback, $this->items);
    }

    /**
     * Pluck specific fields from the collection.
     */
    public function pluck(string $value, ?string $key = null): array
    {
        $results = [];
        foreach ($this->items as $item) {
            $itemValue = $item->{$value} ?? null;
            if ($key) {
                $results[$item->{$key}] = $itemValue;
            } else {
                $results[] = $itemValue;
            }
        }
        return $results;
    }

    /**
     * Join the collection values into a string.
     */
    public function join(string $glue, string $property = 'name'): string
    {
        return implode($glue, $this->pluck($property));
    }

    /**
     * Map to a dropdown array.
     */
    public function toDropdown(string $label = 'name', string $value = 'iso2'): array
    {
        return array_map(function(Country $c) use ($label, $value) {
            return [
                'label' => $c->{$label} ?? $c->name,
                'value' => $c->{$value} ?? $c->iso2,
            ];
        }, $this->items);
    }

    /**
     * Sort the collection.
     */
    public function sortBy(string $property): self
    {
        $items = $this->items;
        usort($items, function(Country $a, Country $b) use ($property) {
            return $a->{$property} <=> $b->{$property};
        });
        return new self($items);
    }

    /**
     * Helper to filter items.
     */
    protected function filter(callable $callback): self
    {
        return new self(array_values(array_filter($this->items, $callback)));
    }

    /**
     * Get the first item.
     */
    public function first(): ?Country
    {
        return $this->items[0] ?? null;
    }

    /**
     * Get all items.
     */
    public function all(): array
    {
        return $this->items;
    }

    // --- Interfaces Implementation ---

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    public function jsonSerialize(): array
    {
        return array_map(fn(Country $c) => $c->toArray(), $this->items);
    }
}
