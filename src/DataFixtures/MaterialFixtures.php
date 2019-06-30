<?php

namespace App\DataFixtures;

use App\Entity\Material;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

final class MaterialFixtures extends Fixture implements DependentFixtureInterface
{
    public const MATERIAL_REFERENCE =  'material';

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $materialEntity = new Material();
        $materialEntity->setName('material1');
        $materialEntity->setIsActive(1);
        $materialEntity->setSerialNumber('25E2D34DE56');
        $materialEntity->setCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE));
        $materialEntity->setUserId($this->getReference(UserFixtures::USER_REFERENCE));
        $manager->persist($materialEntity);
        $manager->flush();

        $material = new Material();
        $material->setName('material2');
        $material->setIsActive(0);
        $material->setSerialNumber('25E2D34DE56');
        $material->setCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE));
        $material->setUserId($this->getReference(UserFixtures::USER_REFERENCE));
        $manager->persist($material);
        $this->addReference(self::MATERIAL_REFERENCE, $material);

        $manager->flush();
    }
    
    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}
