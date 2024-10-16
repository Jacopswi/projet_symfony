<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ImageFixtures extends Fixture
{
    public const IMAGE_REFERENCE = 'Catégorie inconnue';

    public function load(ObjectManager $manager): void
    {
        $imagesData = [
            'https://www.esc-distribution.com/11304-thickbox_default/pulp-fiction-dvd.jpg',
            'https://static.fnac-static.com/multimedia/Images/FR/NR/44/32/6e/7221828/1540-1/tsp20150928140357/Coffret-Scorsese-11-films-DVD.jpg',
            'https://static.fnac-static.com/multimedia/Images/FR/NR/44/32/6e/7221828/1540-1/tsp20150928140357/Coffret-Scorsese-11-films-DVD.jpg',
        ];

        foreach ($imagesData as $imageUrl) {
            $image = new Image();
            $image->setUrl($imageUrl);

            $manager->persist($image);
            $this->addReference(self::IMAGE_REFERENCE . '_' . $key, $image);

        }

        $manager->flush();
    }
}
