<?php

namespace App\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Borrowing;
use App\Entity\User;
use App\Entity\Employee;
use App\Entity\Material;
use App\Repository\BorrowingRepository;
use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

final class BorrowingService extends AbstractController
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
     * @param DateTime $date_start
     * @param DateTime $date_end
     * @return Borrowing
     */
    public function createBorrowing(array $employee, array $material, \DateTime $date_start, \DateTime $date_end): Borrowing
    {
        /** @var User $user */
        $user = $this->getUser();
        $employee = $this->em->getRepository(Employee::class)->find($employee[0]);
        $material = $this->em->getRepository(Material::class)->find($material[0]);
        $borrowingEntity = new Borrowing();
        
        // Le matériel est maintenant indisponible
        $material->setAvailable(0);

        $borrowingEntity->setUser($user);
        $borrowingEntity->setEmployee($employee);
        $borrowingEntity->setMaterial($material);
        $borrowingEntity->setDateStart($date_start);
        $borrowingEntity->setDateEnd($date_end);

        $this->em->persist($borrowingEntity);
        $this->writeLog("Création de la demande du material : ".$material->getName()." pour l'employee : ".$employee->getFirstName().' '.$employee->getFirstName()." # ".date('Y-m-d H:i:s'));

        $this->em->flush();
        return $borrowingEntity;
    }
    
    /**
     * @param integer $id
     * @param string $name
     * @param int $category
     * @param integer $isActive
     * @param string $serialNumber
     * @return Borrowing
     */
    public function updateBorrowing(int $id,array $employee, array $material, \DateTime $date_start, \DateTime $date_end): Borrowing
    {
        $user = $this->getUser();
        $employee = $this->em->getRepository(Employee::class)->find($employee['id']);
        $material = $this->em->getRepository(Material::class)->find($material['id']);
        $borrowingEntity = new Borrowing();

        $borrowing = $this->em->getRepository(Borrowing::class)->find($id);

        $borrowing->setUser($user);
        $borrowing->setEmployee($employee);
        $borrowing->setMaterial($material);
        $borrowing->setDateStart($date_start);
        $borrowing->setDateEnd($date_end);
        
        $this->writeLog("Modification du material : ".$material->getName()." pour l'employee : ".$employee->getFirstName().' '.$employee->getFirstName()." - ".date('Y-m-d H:i:s'));
        $this->em->flush();
        return $borrowing;
    }

      /**
     * @param integer $id
     * @param DateTime $date_restitution
     * @return Borrowing
     */
    public function resituteMaterial(int $id, \DateTime $date_restitution): Borrowing
    {
  

        $borrowing = $this->em->getRepository(Borrowing::class)->find($id);

        $borrowing->setDateRestitution($date_restitution);
        $material = $borrowing->getMaterial();
        
        $this->writeLog("Restitution du material : ".$material->getName()." # ".date('Y-m-d H:i:s'));
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

    public function writeLog($phrase) {
        $chemin = $this->getParameter('ourlogs_directory');
        if (!is_dir($chemin)) {
            mkdir($chemin, 0775, true);
        }
        $chemin_url = $chemin . "/event-log.txt";
        $handle = fopen($chemin_url, "a+");
        fputs($handle, $phrase."\n");
    }
    
}
