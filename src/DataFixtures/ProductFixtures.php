<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Enum\ProductStatus;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public const PRODUCT_REFERENCE = 'Product';

    public function load(ObjectManager $manager): void
    {
        $productsData = [
            [
                'name' => 'DVD Pulp Fiction',
                'price' => 10,
                'description' => 'DVD du film Pulp Fiction',
                'stock' => 200,
                'status' => ProductStatus::Disponible,
                'categories' => [1,2]
            ],
            [
                'name' => 'Coffret Scorcese',
                'price' => 70,
                'description' => 'Coffret de films Scorcese',
                'stock' => 0,
                'status' => ProductStatus::EnRupture,
                'categories' => [3]

            ],
            [
                'name' => 'Poster Les demoiselles de Rochefort',
                'price' => 4,
                'description' => 'Poster de film Les demoiselles de Rochefort',
                'stock' => 43,
                'status' => ProductStatus::EnPrecommande,
                'categories' => [0]

            ],
        ];

        foreach ($productsData as $key => $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setPrice($data['price']);
            $product->setDescription($data['description']);
            $product->setStock($data['stock']);
            $product->setStatus($data['status']);
            $product->setImage($this->getReference(ImageFixtures::IMAGE_REFERENCE . '_' . ($key)));

            foreach ($data['categories'] as $category) {
                $categoryReference = $this->getReference(CategoryFixtures::CATEGORY_REFERENCE . '_' . $category);
                $product->addCategory($categoryReference);
            }


            $manager->persist($product);
            $this->addReference(self::PRODUCT_REFERENCE . '_' . $key, $product);

        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            ImageFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
