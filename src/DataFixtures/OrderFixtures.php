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
                'user' => 0, 
                'orderItems' => [0, 1] 
            ],
            [
                'reference' => 'ORD002',
                'createdAt' => new \DateTimeImmutable(),
                'status' => OrderStatus::Livree,
                'user' => 1,
                'orderItems' => [2]
            ],
            [
                'reference' => 'ORD003',
                'createdAt' => new \DateTimeImmutable(),
                'status' => OrderStatus::Annulee,
                'user' => 2,
                'orderItems' => []
            ],
        ];

        foreach ($ordersData as $key => $data) {
            $order = new Order();
            $order->setReference($data['reference']);
            $order->setCreatedAt($data['createdAt']);
            $order->setStatus($data['status']);
            $order->setUtilisateur($this->getReference(UserFixtures::USER_REFERENCE . '_' . $data['user']));

            foreach ($data['orderItems'] as $orderItemIndex) {
                $order->addOrderItem($this->getReference(OrderItemFixtures::ORDER_ITEM_REFERENCE . '_' . $orderItemIndex));
            }

            $manager->persist($order);
            $this->addReference(self::ORDER_REFERENCE . $key, $order);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            OrderItemFixtures::class,
        ];
    }
}
