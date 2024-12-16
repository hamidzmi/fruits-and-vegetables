<?php

namespace App\Domain\Service;

use App\Domain\Entity\Collection\FruitCollection;
use App\Domain\Entity\Collection\ProductCollection;
use App\Domain\Entity\Collection\VegetableCollection;
use App\Domain\Entity\Product;
use App\Domain\ValueObject\ProductType;

class ProductCollectionManager
{
    private FruitCollection $fruitCollection;
    private VegetableCollection $vegetableCollection;

    public function __construct()
    {
        $this->fruitCollection = new FruitCollection();
        $this->vegetableCollection = new VegetableCollection();
    }

    public function addItem(Product $item): void
    {
        if ($item->getType()->equals(ProductType::fruit)) {
            $this->fruitCollection->add($item);
        } elseif ($item->getType()->equals(ProductType::vegetable)) {
            $this->vegetableCollection->add($item);
        }
    }

    public function getCollections(): array
    {
        return [
            'fruits' => $this->fruitCollection,
            'vegetables' => $this->vegetableCollection,
        ];
    }
}
