<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORY_REFERENCE = 'Catégorie inconnue';

    public function load(ObjectManager $manager): void
    {
        $categoriesData = [
            'DVD',
            'OST',
            'Poster',
            'Blu-Ray',
            'Coffret',
        ];

        foreach ($categoriesData as $key => $categoryName) {
            $category = new Category();
            $category->setNom($categoryName);

            $manager->persist($category);
            $this->addReference(self::CATEGORY_REFERENCE . '_' . $key, $category);

        }

        $manager->flush();
    }
}
