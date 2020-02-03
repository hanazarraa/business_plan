<?php

namespace App\Repository;

use App\Entity\ReccuringInvoicing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ReccuringInvoicing|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReccuringInvoicing|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReccuringInvoicing[]    findAll()
 * @method ReccuringInvoicing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReccuringInvoicingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReccuringInvoicing::class);
    }

    // /**
    //  * @return ReccuringInvoicing[] Returns an array of ReccuringInvoicing objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReccuringInvoicing
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
