<?php

namespace App\Domain\ValueObject;

enum ProductType: string
{
    case fruit = 'fruit';
    case vegetable = 'vegetable';

    public function equals(self $type): bool
    {
        return $type->value === $this->value;
    }
}
