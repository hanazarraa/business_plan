<?php

namespace App\Repository;

use App\Entity\Generalexpensesdetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Generalexpensesdetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method Generalexpensesdetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method Generalexpensesdetail[]    findAll()
 * @method Generalexpensesdetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GeneralexpensesdetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Generalexpensesdetail::class);
    }

    // /**
    //  * @return Generalexpensesdetail[] Returns an array of Generalexpensesdetail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Generalexpensesdetail
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
