<?php

namespace App\Service;

use App\Entity\Type;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


final class TypeService extends AbstractController
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var TypeRepository */
    private $typeRepository;

    /**
     * TypeService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $name
     * @return Type
     */
    public function createType(string $name): Type
    {
        $typeEntity = new Type();
        $typeEntity->setName($name);
        $this->em->persist($typeEntity);
        $this->writeLog("Création du type : <strong>".$name."</strong> # ".date('Y-m-d H:i:s'));
        $this->em->flush();
        return $typeEntity;
    }

    
    /**
     * @param integer $id
     * @param string $name
     * @return Type
     */
    public function updateType(int $id,string $name): Type
    {
        
        $type = $this->em->getRepository(Type::class)->find($id);
        //var_dump($bla);

        $type->setName($name);
        // $type->setQuantity($quantity);
        $this->writeLog("Modification du type : <strong>".$name."</strong> # ".date('Y-m-d H:i:s'));
        $this->em->flush();

        return $type;
    }
    /**
     * @return object[]
     */
    public function getAll(): array
    {
        return $this->em->getRepository(Type::class)->findBy([], ['id' => 'DESC']);
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
