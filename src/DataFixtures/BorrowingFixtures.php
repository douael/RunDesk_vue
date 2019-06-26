<?php

namespace App\DataFixtures;

use App\Entity\Borrowing;
use Carbon\Carbon;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

final class BorrowingFixtures extends Fixture
{
    public const BORROWING_REFERENCE =  'borrowing';

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $borrowingEntity = new Borrowing();
        $borrowingEntity->setDateStart(Carbon::now());
        $borrowingEntity->getDateEnd(Carbon::now());
        $manager->persist($borrowingEntity);
        $manager->flush();

        $borrowing = new Borrowing();
        $borrowing->setDateStart(Carbon::now());
        $borrowing->getDateEnd(Carbon::now());
        $manager->persist($borrowing);
        $this->addReference(self::BORROWING_REFERENCE, $borrowing);

        $manager->flush();
    }

}
