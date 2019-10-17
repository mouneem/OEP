<?php

namespace App\Repository;

use App\Entity\JointFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method JointFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method JointFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method JointFile[]    findAll()
 * @method JointFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JointFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JointFile::class);
    }

    // /**
    //  * @return JointFile[] Returns an array of JointFile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?JointFile
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
