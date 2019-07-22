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
     * @return Borrowing[]
     */
    public function findByDate($date, $delimiter = ',')
    {
        if(!isset($date)){

            $RAW_QUERY = 'SELECT id,date_start,date_end,date_restitution,employee_id,material_id FROM borrowing where borrowing.date_restitution BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY)
            AND NOW();';
        }else{
            $RAW_QUERY = 'SELECT * FROM borrowing where borrowing.date_restitution BETWEEN DATE_SUB(NOW(), INTERVAL "'.$date.'" DAY)
            AND NOW();';
        }
        
        return $this->getEntityManager()->getConnection()->executeQuery($RAW_QUERY)->fetchAll();

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