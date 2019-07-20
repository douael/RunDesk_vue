<?php

namespace App\Repository;

use App\Entity\Borrowing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Borrowing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Borrowing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Borrowing[]    findAll()
 * @method Borrowing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BorrowingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Borrowing::class);
    }

       /**
     * @param integer $id
     * @param string $delimiter
     * @return Borrowing[]
     */
    public function findByMaterial($id, $delimiter = ',')
    {
        // var_dump($id);
        
        $qb = $this->createQueryBuilder('a');

        return $qb
            ->where('a.material =:id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param integer $id
     * @param string $delimiter
     * @return Borrowing[]
     */
    public function findByEmployee($id, $delimiter = ',')
    {
        // var_dump($id);
        
        $qb = $this->createQueryBuilder('a');

        return $qb
            ->where('a.employee =:id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Borrowing[] Returns an array of Borrowing objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Borrowing
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}