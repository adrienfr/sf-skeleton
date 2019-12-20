<?php

namespace App\DataFixtures;

use App\Entity\Flat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $flat = new Flat();
        $flat->setGuests(1);
        $flat->setNote('test');

        $manager->persist($flat);
        $manager->flush();
    }
}
