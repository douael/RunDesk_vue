<?php

namespace App\Service;

use App\Entity\Material;
use Doctrine\ORM\EntityManagerInterface;

final class MaterialService
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * MaterialService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $name
     * @param integer $isActive
     * @param string $serialNumber
     * @return Material
     */
    public function createMaterial(string $name,int $isActive, string $serialNumber): Material
    {
        $materialEntity = new Material();
        $materialEntity->getId($id);
        var_dump($materialEntity);die;
        $materialEntity->setName($name);
        $materialEntity->setIsActive($isActive);
        $materialEntity->setSerialNumber($serialNumber);
        $this->em->persist($materialEntity);
        $this->em->flush();

        return $materialEntity;
    }

    /**
     * @param string $name
     * @param integer $isActive
     * @param string $serialNumber
     * @return Material
     */
    public function editMaterial(int $id, string $name,int $isActive, string $serialNumber): Material
    {
        die;
        $materialEntity = new Material($id);
        $materialEntity->setName($name);
        $materialEntity->setIsActive($isActive);
        $materialEntity->setSerialNumber($serialNumber);
        $this->em->persist($materialEntity);
        $this->em->flush();

        return $materialEntity;
    }
    /**
     * @return object[]
     */
    public function getAll(): array
    {
        return $this->em->getRepository(Material::class)->findBy([], ['id' => 'DESC']);
    }
}
