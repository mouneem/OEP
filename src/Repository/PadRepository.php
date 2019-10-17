<?php

namespace App\Repository;

use App\Entity\Pad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Pad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pad[]    findAll()
 * @method Pad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pad::class);
    }

    // /**
    //  * @return Pad[] Returns an array of Pad objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Pad
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
