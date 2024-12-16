<?php

namespace App\Repository;

use App\Domain\Entity\Collection\ProductCollection;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Domain\ValueObject\ProductType;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SQLiteCollectionRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findByType(ProductType $type): array
    {
        $repository = $this->getEntityManager()->getRepository(Product::class);
        $products = $repository->findBy(['type' => $type->value]);

        $result = [];
        foreach ($products as $product) {
            $result[] = $product->toDomain();
        }

        return $result;
    }

    public function saveCollection(ProductCollection $collection, ProductType $type): void
    {
        foreach ($collection->list() as $domainProduct) {
            $product = (new Product())->fromDomain($domainProduct);
            $this->getEntityManager()->persist($product);
        }

        $this->getEntityManager()->flush();
    }
}
