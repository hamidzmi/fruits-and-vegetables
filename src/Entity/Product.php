<?php

namespace App\Entity;

use App\Domain\Entity\Product as DomainProduct;
use App\Domain\ValueObject\ProductType;
use App\Domain\ValueObject\Weight;
use App\Repository\SQLiteCollectionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SQLiteCollectionRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $externalId;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\Column(type: 'integer')]
    private $weight;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExternalId(): ?int
    {
        return $this->externalId;
    }

    public function setExternalId(int $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function fromDomain(DomainProduct $domainProduct): self
    {
        $this->setExternalId($domainProduct->getId());
        $this->setName($domainProduct->getName());
        $this->setType($domainProduct->getType()->value);
        $this->setWeight($domainProduct->getWeight()->toGrams());

        return $this;
    }

    public function toDomain(): DomainProduct
    {
        return new DomainProduct(
            $this->getExternalId(),
            $this->getName(),
            ProductType::from($this->getType()),
            Weight::fromGrams($this->getWeight())
        );
    }
}
