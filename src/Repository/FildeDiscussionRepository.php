<?php

namespace App\Repository;

use App\Entity\FildeDiscussion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FildeDiscussion|null find($id, $lockMode = null, $lockVersion = null)
 * @method FildeDiscussion|null findOneBy(array $criteria, array $orderBy = null)
 * @method FildeDiscussion[]    findAll()
 * @method FildeDiscussion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FildeDiscussionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FildeDiscussion::class);
    }

    // /**
    //  * @return FildeDiscussion[] Returns an array of FildeDiscussion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FildeDiscussion
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
