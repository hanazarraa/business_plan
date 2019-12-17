<?php

namespace App\Repository;

use App\Entity\UnitInvoicing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UnitInvoicing|null find($id, $lockMode = null, $lockVersion = null)
 * @method UnitInvoicing|null findOneBy(array $criteria, array $orderBy = null)
 * @method UnitInvoicing[]    findAll()
 * @method UnitInvoicing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnitInvoicingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnitInvoicing::class);
    }

    // /**
    //  * @return UnitInvoicing[] Returns an array of UnitInvoicing objects
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
    public function findOneBySomeField($value): ?UnitInvoicing
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
