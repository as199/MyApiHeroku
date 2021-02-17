<?php

namespace App\Repository;

use App\Entity\Promo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Promo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promo[]    findAll()
 * @method Promo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promo::class);
    }

    // /**
    //  * @return Promo[] Returns an array of Promo objects
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
    public function findOneBySomeField($value): ?Promo
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getPromoGroupePrincipal($id = null)
    {
        $results = $this->createQueryBuilder('p')
            ->select('p,g,a')
            ->leftJoin('p.groupes', 'g')
            ->andWhere('g.type =:type')
            ->setParameter('type', "principale")
            ->leftJoin('g.apprenants', 'a')
            ->andWhere('a.status =:archiver')
            ->setParameter('archiver', false);
        if ($id) {
            $results->andWhere('p.id =:idpromo')
                ->setParameter('idpromo', $id);
        }

        // dd($results);
        return $results;
    }

    public function findOneByPomoIdGroupeId($id,$id1): \Doctrine\ORM\Query
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id =:val')
            ->andWhere('p.groupes =:val2')
            ->setParameter('val', $id)
            ->setParameter('val2', $id1)
            ->getQuery();
    }

    public function recupBriefidpromo($val,$id): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.briefMaPromo', 'b')
            ->andWhere('b.promos =:val')
            ->setParameter('val', $val)
            ->andWhere('b.briefs = :id')
            ->setParameter('id', $id);
    }

    public function getReferencielApprenantCompetences( $id1,$id): \Doctrine\ORM\QueryBuilder
    {
        $results= $this->createQueryBuilder('p')
            ->select('p,r,g,c')
            ->leftJoin('p.referenciels','r')
            ->andWhere('r.id =:ids')
            ->setParameter('ids', $id1)
            ->leftJoin('p.apprenants','a')
            ->leftJoin('r.groupeCompetence.', "g")
            ->leftJoin('g.competences','c')
            ->andWhere('p.id =:id')
            ->setParameter('id', $id)
            ;


        //dd($results);
        return $results;
    }
    public function refPromApprenant($idPromo,$idRef): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id=:val')
            ->andWhere('u.referenciels=:p')
            ->setParameter('p', $idRef)
            ->setParameter('val', $idPromo)
        ;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findApprenantAttente()
    {
       return  $this->createQueryBuilder('p')
            ->select('p,g,a')
            ->leftJoin('p.groupes','g')
            ->andWhere('g.type =:type')
            ->setParameter('type', "principale")
            ->leftJoin('g.apprenants','a')
            ->andWhere('a.attente =:attente')
            ->setParameter('attente', true)
            ->andWhere('a.status =:status')
            ->setParameter('status', false);

    }

    public function findFormateur()
    {
        return  $this->createQueryBuilder('p')
            ->select('p,g,f')
            ->leftJoin('p.groupes','g')
            ->andWhere('g.status =:type')
            ->setParameter('type', false)
            ->leftJoin('g.formateurs','f')
            ->andWhere('f.status =:status')
            ->setParameter('status', false);

    }

    public function findApprenantPromoAttente()
    {
        return  $this->createQueryBuilder('p')
            ->select('p,a')
            ->leftJoin('g.apprenants','g')
            ->andWhere('g.attente =:attente')
            ->setParameter('attente', true)
            ->andWhere('a.status =:status')
            ->setParameter('status', false);

    }
}
