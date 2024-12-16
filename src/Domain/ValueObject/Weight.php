<?php

namespace App\Domain\ValueObject;

final class Weight
{
    private int $grams;

    private function __construct(int $grams)
    {
        if ($grams < 0) {
            throw new \InvalidArgumentException("Weight cannot be negative.");
        }

        $this->grams = $grams;
    }

    public static function fromGrams(int $grams): self
    {
        return new self($grams);
    }

    public static function fromKilograms(float $kilograms): self
    {
        return new self((int)round($kilograms * 1000));
    }

    public static function from(int $quantity, string $unit): self
    {
        if ($unit === 'g') {
            return self::fromGrams($quantity);
        }

        if ($unit === 'kg') {
            return self::fromKilograms($quantity);
        }

        throw new \InvalidArgumentException("Unsupported unit: $unit");
    }

    public function toGrams(): int
    {
        return $this->grams;
    }

    public function equals(self $other): bool
    {
        return $this->grams === $other->grams;
    }
}
