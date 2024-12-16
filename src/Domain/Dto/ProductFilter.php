<?php

namespace App\Domain\Dto;

class ProductFilter
{
    private ?string $name;
    private ?int $minWeight;
    private ?int $maxWeight;

    public function __construct(?string $name = null, ?int $minWeight = null, ?int $maxWeight = null)
    {
        $this->name = $name;
        $this->minWeight = $minWeight;
        $this->maxWeight = $maxWeight;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getMinWeight(): ?int
    {
        return $this->minWeight;
    }

    public function getMaxWeight(): ?int
    {
        return $this->maxWeight;
    }

    public function hasFilters(): bool
    {
        return $this->name !== null || $this->minWeight !== null || $this->maxWeight !== null;
    }
}
