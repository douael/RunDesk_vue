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
     * @param integer $date
     * @param string $delimiter
     * @return Category[]
     */
    public function findByDate($date, $delimiter = ',')
    {
        $em = $this->getDoctrine()->getManager();
        if(!isset($date)){

            $RAW_QUERY = 'SELECT * FROM borrowings where borrowings.date_restitution BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY)
            AND NOW();';
        }else{
            $RAW_QUERY = 'SELECT * FROM borrowings where borrowings.date_restitution BETWEEN DATE_SUB("'.$date.'", INTERVAL 30 DAY)
            AND NOW();';
        }
        
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        // Set parameters 
        $statement->execute();

        $result = $statement->fetchAll();
        return $result;
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