<?php

namespace App\Service;

use App\Entity\Borrowing;
use App\Entity\User;
use App\Entity\Employee;
use App\Entity\Material;
use App\Repository\BorrowingRepository;
use Doctrine\ORM\EntityManagerInterface;

final class BorrowingService
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var BorrowingRepository */
    private $borrowingRepository;

    /**
     * BorrowingService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param array $user
     * @param array $employee
     * @param array $material
     * @return Borrowing
     */
    public function createBorrowing(array $user, array $employee, array $material): Borrowing
    {
        $user = $this->em->getRepository(User::class)->find($user['id']);
        $employee = $this->em->getRepository(Employee::class)->find($employee['id']);
        $material = $this->em->getRepository(Material::class)->find($material['id']);
        
        $borrowingEntity = new Borrowing();

        $borrowingEntity->setUser($user);
        $borrowingEntity->setEmployee($employee);
        $borrowingEntity->setMaterial($material);

        $this->em->persist($borrowingEntity);
        $this->em->flush();
        return $borrowingEntity;
    }

    /**
     * @param integer $id
     * @param integer $isActive
     * @return Borrowing
     */
    // public function editBorrowing(int $id,int $isActive): Borrowing
    // {
        
    //     $borrowing = $this->em->getRepository(Borrowing::class)->find($id);
    //     //var_dump($bla);

    //     $borrowing->setIsActive($isActive);
    //     $this->em->flush();

    //     return $borrowing;
    // }
    
    /**
     * @param integer $id
     * @param string $name
     * @param int $category
     * @param integer $isActive
     * @param string $serialNumber
     * @return Borrowing
     */
    public function updateBorrowing(int $id,string $name, int $isActive,string $serialNumber, int $category): Borrowing
    {
        $category = $this->em->getRepository(Category::class)->find($category);

        $borrowing = $this->em->getRepository(Borrowing::class)->find($id);
        //var_dump($bla);

        $borrowing->setIsActive($isActive);
        $borrowing->setName($name);
        $borrowing->setSerialNumber($serialNumber);
        $borrowing->setCategory($category);
        $this->em->flush();

        return $borrowing;
    }
    /**
     * @return object[]
     */
    public function getAll(): array
    {
        return $this->em->getRepository(Borrowing::class)->findBy([], ['id' => 'DESC']);
    }

    
}
