<?php


namespace App\Service;


use App\Repository\ApprenantRepository;

class ProfilSortiService
{
    /**
     * @var ApprenantRepository
     */
    private ApprenantRepository $apprenant;

    /**
     * ProfilSortiService constructor.
     */
    public function __construct(ApprenantRepository $apprenant )
    {
        $this->apprenant = $apprenant;
    }
    public function getApprenanttProfilSorti($valu ,$id): \Doctrine\ORM\QueryBuilder
    {
        return $this->apprenant->recuperApprenants($valu ,$id);
    }
}