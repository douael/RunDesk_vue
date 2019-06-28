<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

final class CategoryService
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
     * @param integer $type
     * @param integer $quantity
     * @return Category
     */
    public function createCategory(string $name,int $type, int $quantity): Category
    {
        $categoryEntity = new Category();
        $categoryEntity->setName($name);
        $categoryEntity->setType($type);
        $categoryEntity->setQuantity($quantity);
        $this->em->persist($categoryEntity);
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
     * @param integer $type
     * @param integer $quantity
     * @return Category
     */
    public function updateCategory(int $id,string $name, int $type,int $quantity): Category
    {
        
        $category = $this->em->getRepository(Category::class)->find($id);
        //var_dump($bla);

        $category->setName($name);
        $category->setType($type);
        $category->setQuantity($quantity);
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
}
