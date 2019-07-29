<?php

namespace App\Service;

use App\Entity\Material;
use App\Entity\Category;
use App\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


final class MaterialService extends AbstractController
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
     * @param array $category
     * @param string $serialNumber
     * @return Material
     */
    public function createMaterial(string $name,int $isActive, string $serialNumber, array $category): Material
    {
        /** @var User $user */
        $user = $this->getUser();
        $category = $this->em->getRepository(Category::class)->find($category[0]);
        $materialEntity = new Material();
        $materialEntity->setName($name);
        $materialEntity->setIsActive($isActive);
        $materialEntity->setSerialNumber($serialNumber);
        $materialEntity->setCategory($category);
        $materialEntity->setUserId($user);
        $materialEntity->setAvailable(1);

        $this->em->persist($materialEntity);
        $this->em->flush();
        $this->writeLog("Création de Materiel : <strong>".$name."</strong> # ".date('Y-m-d H:i:s'));
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
        $this->writeLog("Changement de statut : <strong>".$material->getName()."</strong> # ".date('Y-m-d H:i:s'));

        $this->em->flush();

        return $material;
    }

    /**
     * @param integer $id
     * @param integer $available
     * @return Material
     */
    public function availableMaterial(int $id,int $available): Material
    {
        
        $material = $this->em->getRepository(Material::class)->find($id);
        //var_dump($bla);

        $material->setAvailable($available);
        $this->writeLog("Materiel de nouveau disponible : <strong>".$material->getName()."</strong> # ".date('Y-m-d H:i:s'));

        $this->em->flush();

        return $material;
    }
    
    /**
     * @param integer $id
     * @param string $name
     * @param int $category
     * @param integer $isActive
     * @param string $serialNumber
     * @return Material
     */
    public function updateMaterial(int $id,string $name, int $isActive,string $serialNumber, int $category): Material
    {
        $category = $this->em->getRepository(Category::class)->find($category);

        $material = $this->em->getRepository(Material::class)->find($id);
        //var_dump($bla);

        $material->setIsActive($isActive);
        $material->setName($name);
        $material->setSerialNumber($serialNumber);
        $material->setCategory($category);
        $this->writeLog("Modification du materiel : <strong>".$material->getName()."</strong> # ".date('Y-m-d H:i:s'));
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

    public function writeLog($phrase) {
        $chemin = $this->getParameter('logs_directory');
        if (!is_dir($chemin)) {
            mkdir($chemin, 0775, true);
        }
        $chemin_url = $chemin . "/event-log.txt";
        $handle = fopen($chemin_url, "a+");
        fputs($handle, $phrase."\n");
    }
    
}
