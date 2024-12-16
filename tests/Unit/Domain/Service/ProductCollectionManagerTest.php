<?php

namespace App\Tests\Unit\Domain\Service;


use App\Domain\Entity\Collection\FruitCollection;
use App\Domain\Entity\Collection\VegetableCollection;
use App\Domain\Entity\Product;
use App\Domain\Service\ProductCollectionManager;
use App\Domain\ValueObject\ProductType;
use App\Domain\ValueObject\Weight;
use PHPUnit\Framework\TestCase;

class ProductCollectionManagerTest extends TestCase
{
    public function testAddItemToFruitCollection(): void
    {
        $manager = new ProductCollectionManager();

        $fruit = new Product(1, 'Apple', ProductType::from('fruit'), Weight::from(500, 'g'));
        $manager->addItem($fruit);

        $collections = $manager->getCollections();
        /** @var FruitCollection $fruitCollection */
        $fruitCollection = $collections['fruits'];

        $this->assertCount(1, $fruitCollection->list());
        $this->assertSame($fruit, $fruitCollection->list()[1]);
    }

    public function testAddItemToVegetableCollection(): void
    {
        $manager = new ProductCollectionManager();

        $vegetable = new Product(2, 'Carrot', ProductType::from('vegetable'), Weight::from(1000, 'g'));
        $manager->addItem($vegetable);

        $collections = $manager->getCollections();
        /** @var VegetableCollection $vegetableCollection */
        $vegetableCollection = $collections['vegetables'];

        $this->assertCount(1, $vegetableCollection->list());
        $this->assertSame($vegetable, $vegetableCollection->list()[2]);
    }

    public function testAddInvalidTypeThrowsException(): void
    {
        $this->expectException(\ValueError::class); // Modify as needed if specific validation for unsupported types is implemented.

        $manager = new ProductCollectionManager();

        $invalidProduct = new Product(3, 'Unknown', ProductType::from('unknown'), Weight::from(300, 'g'));
        $manager->addItem($invalidProduct);
    }

    public function testGetCollectionsReturnsBothCollections(): void
    {
        $manager = new ProductCollectionManager();

        $fruit = new Product(1, 'Banana', ProductType::from('fruit'), Weight::from(600, 'g'));
        $vegetable = new Product(2, 'Tomato', ProductType::from('vegetable'), Weight::from(300, 'g'));

        $manager->addItem($fruit);
        $manager->addItem($vegetable);

        $collections = $manager->getCollections();

        $this->assertArrayHasKey('fruits', $collections);
        $this->assertArrayHasKey('vegetables', $collections);

        /** @var FruitCollection $fruitCollection */
        $fruitCollection = $collections['fruits'];
        $this->assertCount(1, $fruitCollection->list());
        $this->assertSame($fruit, $fruitCollection->list()[1]);

        /** @var VegetableCollection $vegetableCollection */
        $vegetableCollection = $collections['vegetables'];
        $this->assertCount(1, $vegetableCollection->list());
        $this->assertSame($vegetable, $vegetableCollection->list()[2]);
    }
}