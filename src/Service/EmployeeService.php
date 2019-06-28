<?php

namespace App\Service;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;

final class EmployeeService
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
     * @param array $user_id
     * @return Employee
     */
    public function createEmployee(string $lastname, string $firstname, string $site, array $user_id): Employee
    {
        $employeeEntity = new Employee();
        $employeeEntity->setLastname($lastname);
        $employeeEntity->setFirstname($firstname);
        $employeeEntity->setSite($site);
        $employeeEntity->setUserId($user_id);
        $this->em->persist($employeeEntity);
        $this->em->flush();
        return $employeeEntity;
    }
    
    /**
     * @param integer $id
     * @param string $lastname
     * @param string $firstname
     * @param string $site
     * @param array $user_id
     * @return Employee
     */
    public function updateEmployee(int $lastname,string $firstname, int $site,int $user_id): Employee
    {
        
        $employee = $this->em->getRepository(Employee::class)->find($id);

        $employee->setLastname($lastname);
        $employee->setFirstname($firstname);
        $employee->setSite($site);
        $employee->setUserId($user_id);
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
}
