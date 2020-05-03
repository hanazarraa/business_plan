<?php

namespace App\Repository;

use App\Entity\Investmentsdetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Investmentsdetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method Investmentsdetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method Investmentsdetail[]    findAll()
 * @method Investmentsdetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvestmentsdetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Investmentsdetail::class);
    }

    // /**
    //  * @return Investmentsdetail[] Returns an array of Investmentsdetail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Investmentsdetail
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
