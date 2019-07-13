<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Type;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


final class CategoryService extends AbstractController
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var CategoryRepository */
    private $categoryRepository;

    /**
     * CategoryService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $name
     * @param array $type
     * @return Category
     */
    public function createCategory(string $name,array $type): Category
    {
        $categoryEntity = new Category();
        $categoryEntity->setName($name);

        $type = $this->em->getRepository(Type::class)->find($type['id']);
        $categoryEntity->setType($type);

        $this->em->persist($categoryEntity);
        $this->writeLog("Création Categorie : <strong>".$name."</strong> # ".date('Y-m-d H:i:s'));
        $this->em->flush();
        return $categoryEntity;
    }

    /**
     * @param integer $id
     * @param integer $isActive
     * @return Category
     */
    public function editCategory(int $id,int $isActive): Category
    {
        
        $category = $this->em->getRepository(Category::class)->find($id);
        //var_dump($bla);

        $category->setIsActive($isActive);
        $this->em->flush();

        return $category;
    }
    
    /**
     * @param integer $id
     * @param string $name
     * @param array $type
     * @return Category
     */
    public function updateCategory(int $id,string $name, array $type): Category
    {
        
        $category = $this->em->getRepository(Category::class)->find($id);
        //var_dump($bla);

        $category->setName($name);
        $category->setType($type);
        // $category->setQuantity($quantity);
        $this->writeLog("Modification de la Categorie : <strong>".$name."</strong> # ".date('Y-m-d H:i:s'));
        $this->em->flush();

        return $category;
    }
    /**
     * @return object[]
     */
    public function getAll(): array
    {
        return $this->em->getRepository(Category::class)->findBy([], ['id' => 'DESC']);
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
