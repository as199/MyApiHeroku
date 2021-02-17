<?php

namespace App\Controller;

use App\Repository\ApprenantRepository;
use App\Repository\BriefRepository;
use App\Repository\GroupeRepository;
use App\Repository\PromoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class BriefController extends AbstractController
{
    /**
     * @var GroupeRepository
     */
    private GroupeRepository $groupeRepository;
    /**
     * @var PromoRepository
     */
    private PromoRepository $promoRepository;
    /**
     * @var BriefRepository
     */
    private BriefRepository $briefRepository;
    /**
     * @var ApprenantRepository
     */
    private ApprenantRepository $apprenantRepository;
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * BriefController constructor.
     */
    public function __construct(SerializerInterface $serializer, ApprenantRepository $apprenantRepository, PromoRepository $promoRepository,GroupeRepository $groupeRepository,BriefRepository $briefRepository)
    {
        $this->promoRepository= $promoRepository;
        $this->groupeRepository =$groupeRepository;
        $this->briefRepository =$briefRepository;
        $this->apprenantRepository =$apprenantRepository;
        $this->serializer =$serializer;
    }

    public function  getBrief($promo, $id){
        $promo_groups = $this->promoRepository->findOneByPomoIdGroupeId($promo,$id);
        //dd($promo_groups);

        if (!$promo_groups) {
            return $this->json([
                'message' => 'not found!!!'
            ]);
        }
//return $promo_groups;
        return $this->json($promo_groups, Response::HTTP_OK, [], ['groups' => 'brief_groupe:read']);


    }


}
