<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\OrderItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Enum\OrderStatus;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public const ORDER_REFERENCE = 'order';

    public function load(ObjectManager $manager): void
    {
        $ordersData = [
            [
                'reference' => 'ORD001',
                'createdAt' => new \DateTimeImmutable(),
                'status' => OrderStatus::EnPreparation,
                'utilisateur' => 0, 
            ],
            [
                'reference' => 'ORD002',
                'createdAt' => new \DateTimeImmutable(),
                'status' => OrderStatus::Livree,
                'utilisateur' => 1,
            ]
        ];

        foreach ($ordersData as $key => $data) {
            $order = new Order();
            $order->setReference($data['reference']);
            $order->setCreatedAt($data['createdAt']);
            $order->setStatus($data['status']);
            $order->setUtilisateur($this->getReference(UserFixtures::USER_REFERENCE . '_' . $data['utilisateur']));


            $manager->persist($order);
            $this->addReference(self::ORDER_REFERENCE . '_' . $key, $order);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
