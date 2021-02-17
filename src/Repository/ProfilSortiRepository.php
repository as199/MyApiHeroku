<?php

namespace App\Repository;

use App\Entity\ProfilSorti;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProfilSorti|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfilSorti|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfilSorti[]    findAll()
 * @method ProfilSorti[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfilSortiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfilSorti::class);
    }



    // /**
    //  * @return ProfilSorti[] Returns an array of ProfilSorti objects
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
    public function findOneBySomeField($value): ?ProfilSorti
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
