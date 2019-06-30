<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class EmployeeFixtures extends Fixture implements DependentFixtureInterface
{
    public const EMPLOYEE_REFERENCE =  'employee';

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {

        $employeeEntity = new Employee();
        $employeeEntity->setFirstname('employee1');
        $employeeEntity->setLastname('bla');
        $employeeEntity->setSite('Paris');
        $employeeEntity->setUserId($this->getReference(UserFixtures::USER_REFERENCE));
        $manager->persist($employeeEntity);
        $manager->flush();

        $employeeEntity = new Employee();
        $employeeEntity->setFirstname('Echo');
        $employeeEntity->setLastname('bla');
        $employeeEntity->setSite('Tours');
        $employeeEntity->setUserId($this->getReference(UserFixtures::USER_REFERENCE));
        $manager->persist($employeeEntity);
        $this->addReference(self::EMPLOYEE_REFERENCE, $employeeEntity);

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
            UserFixtures::class
        ];
    }
}
