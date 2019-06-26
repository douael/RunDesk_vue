<?php

namespace App\Service;

use App\Entity\Material;
use App\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;

final class MaterialService
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var MaterialRepository */
    private $materialRepository;

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
     * @param integer $id
     * @param integer $isActive
     * @return Material
     */
    public function editMaterial(int $id,int $isActive): Material
    {
        
        $material = $this->em->getRepository(Material::class)->find($id);
        //var_dump($bla);

        $material->setIsActive($isActive);
        $this->em->flush();

        return $material;
    }
    /**
     * @return object[]
     */
    public function getAll(): array
    {
        return $this->em->getRepository(Material::class)->findBy([], ['id' => 'DESC']);
    }
}
