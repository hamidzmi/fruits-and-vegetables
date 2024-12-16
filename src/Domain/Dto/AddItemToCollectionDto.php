<?php

namespace App\Domain\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddItemToCollectionDto
{
    #[Assert\NotBlank(message: "ID is required.")]
    private string $id;

    #[Assert\NotBlank(message: "Name is required.")]
    private string $name;

    #[Assert\NotBlank(message: "Quantity is required.")]
    #[Assert\Positive(message: "Quantity must be a positive number.")]
    private int $quantity;

    #[Assert\NotBlank(message: "Unit is required.")]
    #[Assert\Choice(choices: ['g', 'kg'], message: "Unit must be 'g' or 'kg'.")]
    private string $unit;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->quantity = $data['quantity'] ?? 0;
        $this->unit = $data['unit'] ?? '';
    }

    public function validate(): void
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $violations = $validator->validate($this);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }
            throw new \InvalidArgumentException(implode(', ', $errors));
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }
}
