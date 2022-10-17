<?php

namespace App\DataFixtures;

use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $order = new Order();
            $order ->setUserId($i);
            $order ->setAmount(mt_rand(10, 100));
            $manager->persist($order );
        }

        $manager->flush();
    }
}