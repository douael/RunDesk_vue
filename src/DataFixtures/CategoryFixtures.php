<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture  implements DependentFixtureInterface
{
    public const CATEGORY_REFERENCE =  'category';

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {

        $categoryEntity = new Category();
        $categoryEntity->setName('category1');
        $categoryEntity->setType($this->getReference(TypeFixtures::TYPE_REFERENCE));
        // $categoryEntity->setQuantity('12');
        $manager->persist($categoryEntity);
        $manager->flush();

        $category = new Category();
        $category->setName('category2');
        $category->setType($this->getReference(TypeFixtures::TYPE_REFERENCE));
        // $category->setQuantity('10');
        $manager->persist($categoryEntity);
        $this->addReference(self::CATEGORY_REFERENCE, $category);

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
            TypeFixtures::class
        ];
    }
}
