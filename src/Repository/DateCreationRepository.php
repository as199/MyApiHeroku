<?php

namespace App\Repository;

use App\Entity\DateCreation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DateCreation|null find($id, $lockMode = null, $lockVersion = null)
 * @method DateCreation|null findOneBy(array $criteria, array $orderBy = null)
 * @method DateCreation[]    findAll()
 * @method DateCreation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DateCreationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DateCreation::class);
    }

    // /**
    //  * @return DateCreation[] Returns an array of DateCreation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DateCreation
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
