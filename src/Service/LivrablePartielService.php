<?php


namespace App\Service;


use App\Repository\ApprenantRepository;
use App\Repository\LivrablePartielRepository;
use App\Repository\PromoRepository;
use Doctrine\ORM\QueryBuilder;

class LivrablePartielService
{
    private $apprenantRepository;
    /**
     * @var PromoRepository
     */
    private PromoRepository $promoRepository;


    /**
     * LivrablePartielService constructor.
     */
    public function __construct(PromoRepository $promoRepository)
    {

        $this->promoRepository =$promoRepository;
    }

    /**
     * @param $id1 promo
     * @param $id referenciel
     * @return QueryBuilder
     */
    public function getReferencielApprenantCompetence(int $id1, int $id): QueryBuilder
    {
        return $this->promoRepository->refPromApprenant($id1,$id);
    }
}