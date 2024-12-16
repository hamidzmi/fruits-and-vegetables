<?php

namespace App\Domain\ValueObject;

enum ProductUnit: string
{
    case gram = 'g';
    case kilogram = 'kg';

    public function equals(self $unit): bool
    {
        return $unit->value === $this->value;
    }
}
