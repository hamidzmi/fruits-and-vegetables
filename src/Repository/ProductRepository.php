<?php

namespace App\Repository;

use App\Domain\Dto\ProductFilter;
use App\Domain\Entity\Collection\ProductCollection;
use App\Domain\Entity\Product as DomainProduct;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Domain\ValueObject\ProductType;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function saveCollection(ProductCollection $collection): void
    {
        foreach ($collection->list() as $domainItem) {
            $product = (new Product())->fromDomain($domainItem);
            $this->getEntityManager()->persist($product);
        }

        $this->getEntityManager()->flush();
    }

    public function findByExternalId(int $value): ?DomainProduct
    {
        /** @var Product $item */
        $item = $this->createQueryBuilder('p')
            ->andWhere('p.externalId = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if (empty($item)) {
            return null;
        }

        return $item->toDomain();
    }

    public function findByTypeAndFilter(ProductType $type, ProductFilter $filter): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.type = :type')
            ->setParameter('type', $type->value);

        if ($filter->getName()) {
            $qb->andWhere('p.name LIKE :name')
                ->setParameter('name', '%' . $filter->getName() . '%');
        }

        if ($filter->getMinWeight()) {
            $qb->andWhere('p.weight >= :minWeight')
                ->setParameter('minWeight', $filter->getMinWeight());
        }

        if ($filter->getMaxWeight()) {
            $qb->andWhere('p.weight <= :maxWeight')
                ->setParameter('maxWeight', $filter->getMaxWeight());
        }

        $items = $qb->getQuery()->getResult();

        if (empty($items)) {
            return [];
        }

        $result = [];
        foreach ($items as $item) {
            $result[] = $item->toDomain();
        }

        return $result;
    }
}
