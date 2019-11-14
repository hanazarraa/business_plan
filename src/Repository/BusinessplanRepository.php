<?php

namespace App\Repository;

use App\Entity\Businessplan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Businessplan|null find($id, $lockMode = null, $lockVersion = null)
 * @method Businessplan|null findOneBy(array $criteria, array $orderBy = null)
 * @method Businessplan[]    findAll()
 * @method Businessplan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BusinessplanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Businessplan::class);
    }

    // /**
    //  * @return Businessplan[] Returns an array of Businessplan objects
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
    public function findOneBySomeField($value): ?Businessplan
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
