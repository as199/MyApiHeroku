<?php

namespace App\Repository;

use App\Entity\Apprenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Apprenant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Apprenant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Apprenant[]    findAll()
 * @method Apprenant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApprenantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Apprenant::class);
    }

    // /**
    //  * @return Apprenant[] Returns an array of Apprenant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    */
    public function findByExampleField($id1,$id2,$id3)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.id= :idA')
            ->setParameter('idA', $id1)
            ->innerJoin('a.promo', 'p')
            ->andWhere('p.id= :idP')
            ->setParameter('idP', $id2)
            ->innerJoin('p.referentiel', 'r')
            ->andWhere('r.id= :idR')
            ->setParameter('idR', $id3)
            ->getQuery()
            ->getResult()
            ;
    }
    public function recuperApprenants($value,$id): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.promo = :val')
            ->setParameter('val', $value)
            ->leftJoin('a.profilSorti', 'p')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ;
    }
    public function getApprenantProfilSorti($id1=null): \Doctrine\ORM\QueryBuilder
    {
       $resul =  $this->createQueryBuilder('a')
            ->leftJoin('a.promo', 'p')
           ->andWhere('p.id=:idP')
           ->leftJoin('a.profilSorti','pr')
           ->andWhere('pr.id.id== ')
           ->setParameter('idP', 1)
           ->getQuery()
           ->getResult();
            /*->andWhere('p.id=:idP')
            ->setParameter('idP', $id1);*/
       dd($resul);
    }
    public function getApprenantCompetence($id1,$id): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.promo', 'p')
            ->andWhere('p.id=:idP')
            ->setParameter('idP', $id1)
            ->innerJoin('p.referenciels', 'r')
            ->andWhere('r.id=:idR')
            ->setParameter('idR', $id);
    }
    /*
    public function findOneBySomeField($value): ?Apprenant
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
