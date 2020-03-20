<?php

namespace App\Repository;

use App\Entity\Salesdetailled;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Salesdetailled|null find($id, $lockMode = null, $lockVersion = null)
 * @method Salesdetailled|null findOneBy(array $criteria, array $orderBy = null)
 * @method Salesdetailled[]    findAll()
 * @method Salesdetailled[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalesdetailledRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Salesdetailled::class);
    }

    // /**
    //  * @return Salesdetailled[] Returns an array of Salesdetailled objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Salesdetailled
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
