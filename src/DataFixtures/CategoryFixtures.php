<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture 
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
        $categoryEntity->setType(1);
        // $categoryEntity->setQuantity('12');
        $manager->persist($categoryEntity);
        $manager->flush();

        $category = new Category();
        $category->setName('category2');
        $category->setType(2);
        // $category->setQuantity('10');
        $manager->persist($categoryEntity);
        $this->addReference(self::CATEGORY_REFERENCE, $category);

        $manager->flush();
    }
}
