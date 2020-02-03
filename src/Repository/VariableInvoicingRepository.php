<?php

namespace App\Repository;

use App\Entity\VariableInvoicing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method VariableInvoicing|null find($id, $lockMode = null, $lockVersion = null)
 * @method VariableInvoicing|null findOneBy(array $criteria, array $orderBy = null)
 * @method VariableInvoicing[]    findAll()
 * @method VariableInvoicing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VariableInvoicingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VariableInvoicing::class);
    }

    // /**
    //  * @return VariableInvoicing[] Returns an array of VariableInvoicing objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VariableInvoicing
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
