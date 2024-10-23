<?php

namespace App\DataFixtures;

use App\Entity\OrderItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class OrderItemFixtures extends Fixture implements DependentFixtureInterface
{
    public const ORDERITEM_REFERENCE = 'OrderItem';

    public function load(ObjectManager $manager): void
    {
        $orderItemsData = [
            [
                'quantity' => 2,
                'product' => 0, 
                'orders' => [0, 1] 
            ],
            [
                'quantity' => 1,
                'product' => 1,
                'orders' => [1]
            ],
            [
                'quantity' => 3,
                'product' => 2,
                'orders' => [0]
            ],
        ];

        foreach ($orderItemsData as $key => $data) {
            $orderItem = new OrderItem();
            $orderItem->setQuantity($data['quantity']);
            
            $product = $this->getReference(ProductFixtures::PRODUCT_REFERENCE . '_' . $data['product']);
            $orderItem->setProduct($product);

            $orderItem->setProductPrice($product->getPrice());

            foreach ($data['orders'] as $orderReference) {
                $order = $this->getReference(OrderFixtures::ORDER_REFERENCE . '_' . $orderReference);
                $orderItem->setOrder($order);
            }

            $manager->persist($orderItem);
            $this->addReference(self::ORDERITEM_REFERENCE . '_' . $key, $orderItem);

        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProductFixtures::class,
            OrderFixtures::class,
        ];
    }
}
