<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture
{
    public const ADDRESS_REFERENCE = 'Adresse';

    public function load(ObjectManager $manager): void
    {
        $addressesData = [
            [
                'street' => '123 Main St',
                'postalCode' => '75001',
                'city' => 'Paris',
                'country' => 'France',
            ],
            [
                'street' => '456 High Street',
                'postalCode' => '10001',
                'city' => 'New York',
                'country' => 'USA',
            ],
            [
                'street' => '789 Elm St',
                'postalCode' => 'SW1A 1AA',
                'city' => 'London',
                'country' => 'UK',
            ],
        ];

        foreach ($addressesData as $key => $data) {
            $address = new Address();
            $address->setStreet($data['street'])
                    ->setPostalCode($data['postalCode'])
                    ->setCity($data['city'])
                    ->setCountry($data['country']);

            $manager->persist($address);
            $this->addReference(self::ADDRESS_REFERENCE . '_' . $key, $address);

        }

        $manager->flush();
    }
}
