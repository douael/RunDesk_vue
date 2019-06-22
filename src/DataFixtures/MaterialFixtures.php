<?php

namespace App\DataFixtures;

use App\Entity\Material;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

final class MaterialFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $materialEntity = new Material();
        $materialEntity->setName('material1');
        $materialEntity->setIsActive(true);
        $materialEntity->setSerialNumber('25E2D34DE56');
        $manager->persist($materialEntity);
        $manager->flush();

        $materialEntity = new Material();
        $materialEntity->setName('material2');
        $materialEntity->setIsActive(false);
        $materialEntity->setSerialNumber('25E2D34DE56');
        $manager->persist($materialEntity);
        $manager->flush();
    }
}
