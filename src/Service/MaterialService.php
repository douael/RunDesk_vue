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
     * @return Material
     */
    public function createMaterial(string $name,boolean $isActive, string $serialNum): Material
    {
        $materialEntity = new Material();
        $materialEntity->setName($name);
        $materialEntity->setIsActive($isActive);
        $materialEntity->setSerialNumber($serialNum);
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
