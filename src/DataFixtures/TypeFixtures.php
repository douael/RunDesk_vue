<?php

namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TypeFixtures extends Fixture 
{
    public const TYPE_REFERENCE =  'type';

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {

        $typeEntity = new Type();
        $typeEntity->setName('type1');
        // $typeEntity->setQuantity('12');
        $manager->persist($typeEntity);
        $manager->flush();

        $type = new Type();
        $type->setName('type2');
        // $type->setQuantity('10');
        $manager->persist($typeEntity);
        $this->addReference(self::TYPE_REFERENCE, $type);

        $manager->flush();
    }
}
