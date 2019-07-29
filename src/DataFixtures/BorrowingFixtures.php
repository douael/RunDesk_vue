<?php

namespace App\DataFixtures;

use App\Entity\Borrowing;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BorrowingFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {

        $borrowingEntity = new Borrowing();
        $borrowingEntity->setDateStart(new \DateTime('2014-06-23'));
        $borrowingEntity->setDateEnd(new \DateTime('2014-07-23'));
        $borrowingEntity->setUser($this->getReference(UserFixtures::USER_REFERENCE));
        $borrowingEntity->setEmployee($this->getReference(EmployeeFixtures::EMPLOYEE_REFERENCE));
        $borrowingEntity->setMaterial($this->getReference(MaterialFixtures::MATERIAL_REFERENCE));
        $manager->merge($borrowingEntity);
        $manager->flush();

        $borrowing = new Borrowing();
        $borrowingEntity->setDateStart(new \DateTime('2014-06-21'));
        $borrowingEntity->setDateEnd(new \DateTime('2014-06-23'));
        $borrowingEntity->setUser($this->getReference(UserFixtures::USER_REFERENCE));
        $borrowingEntity->setEmployee($this->getReference(EmployeeFixtures::EMPLOYEE_REFERENCE));
        $borrowingEntity->setMaterial($this->getReference(MaterialFixtures::MATERIAL_REFERENCE));
        $manager->merge($borrowingEntity);

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
            UserFixtures::class,
            EmployeeFixtures::class,
            MaterialFixtures::class
        ];
    }
}
