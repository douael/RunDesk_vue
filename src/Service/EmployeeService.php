<?php

namespace App\Service;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;

final class EmployeeService extends AbstractController
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var EmployeeRepository */
    private $employeeRepository;

    /**
     * EmployeeService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $lastname
     * @param string $firstname
     * @param string $site
     * @return Employee
     */
    public function createEmployee(string $lastname, string $firstname, string $site): Employee
    {
        /** @var User $user */
        $user = $this->getUser();
        $employeeEntity = new Employee();
        $employeeEntity->setLastname($lastname);
        $employeeEntity->setFirstname($firstname);
        $employeeEntity->setSite($site);
        $employeeEntity->setUserId($user);
        $this->em->persist($employeeEntity);
        $this->writeLog("Création de l'employe : ".$firstname." ".$lastname." # ".date('d-m-Y H:i:s'));
        $this->em->flush();
        return $employeeEntity;
    }
    
    /**
     * @param integer $id
     * @param string $lastname
     * @param string $firstname
     * @param string $site
     * @return Employee
     */
    public function updateEmployee(int $id,string $firstname,string $lastname, string $site): Employee
    {
        
        $employee = $this->em->getRepository(Employee::class)->find($id);

        $employee->setLastname($lastname);
        $employee->setFirstname($firstname);
        $employee->setSite($site);
        $this->writeLog("Modification de l'employe : ".$firstname." ".$lastname." # ".date('d-m-Y H:i:s'));
        $this->em->flush();

        return $employee;
    }
    /**
     * @return object[]
     */
    public function getAll(): array
    {
        return $this->em->getRepository(Employee::class)->findBy([], ['id' => 'DESC']);
    }

    public function writeLog($phrase) {
        $chemin = $this->getParameter('logs_directory');
        if (!is_dir($chemin)) {
            mkdir($chemin, 0775, true);
        }
        $a = htmlentities($phrase);
        $phrase_decode = html_entity_decode($a);

        $chemin_url = $chemin . "/event-log.txt";
        $nouveau_contenu = html_entity_decode($a."\r\n");

        $nouveau_contenu .= file_get_contents($chemin_url);

        file_put_contents($chemin_url, $nouveau_contenu);
    }
}
