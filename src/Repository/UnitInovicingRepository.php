<?php

namespace App\Repository;

use App\Entity\UnitInovicing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UnitInovicing|null find($id, $lockMode = null, $lockVersion = null)
 * @method UnitInovicing|null findOneBy(array $criteria, array $orderBy = null)
 * @method UnitInovicing[]    findAll()
 * @method UnitInovicing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnitInovicingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnitInovicing::class);
    }

    // /**
    //  * @return UnitInovicing[] Returns an array of UnitInovicing objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UnitInovicing
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
