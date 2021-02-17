<?php

namespace App\Repository;

use App\Entity\Referenciel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Referenciel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Referenciel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Referenciel[]    findAll()
 * @method Referenciel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferencielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Referenciel::class);
    }

    // /**
    //  * @return Referenciel[] Returns an array of Referenciel objects
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
    public function findOneBySomeField($value): ?Referenciel
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
