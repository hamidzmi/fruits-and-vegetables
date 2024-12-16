<?php

namespace App\Domain\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class ProcessRequestDto
{
    #[Assert\All([
        new Assert\Collection([
            'id' => new Assert\NotBlank(message: "Product ID is required."),
            'name' => new Assert\NotBlank(message: "Product name is required."),
            'type' => new Assert\Choice(choices: ['fruit', 'vegetable'], message: "Type must be 'fruit' or 'vegetable'."),
            'quantity' => [
                new Assert\NotBlank(message: "Quantity is required."),
                new Assert\Positive(message: "Quantity must be a positive number."),
            ],
            'unit' => new Assert\Choice(choices: ['g', 'kg'], message: "Unit must be 'g' or 'kg'."),
        ])
    ])]
    private array $products;

    public function __construct(array $data)
    {
        $this->products = $data;
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

    public function getProducts(): array
    {
        return $this->products;
    }
}
