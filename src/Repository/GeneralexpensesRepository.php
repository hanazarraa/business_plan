<?php

namespace App\Repository;

use App\Entity\Generalexpenses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Generalexpenses|null find($id, $lockMode = null, $lockVersion = null)
 * @method Generalexpenses|null findOneBy(array $criteria, array $orderBy = null)
 * @method Generalexpenses[]    findAll()
 * @method Generalexpenses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GeneralexpensesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Generalexpenses::class);
    }

    // /**
    //  * @return Generalexpenses[] Returns an array of Generalexpenses objects
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
    public function findOneBySomeField($value): ?Generalexpenses
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
