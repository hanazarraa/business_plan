<?php

namespace App\Repository;

use App\Entity\Staffdetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Staffdetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method Staffdetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method Staffdetail[]    findAll()
 * @method Staffdetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StaffdetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Staffdetail::class);
    }

    // /**
    //  * @return Staffdetail[] Returns an array of Staffdetail objects
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
    public function findOneBySomeField($value): ?Staffdetail
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
