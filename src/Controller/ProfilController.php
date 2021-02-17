<?php

namespace App\Controller;

use App\Repository\GroupeRepository;
use App\Repository\ProfilRepository;
use App\Repository\ProfilSortiRepository;
use App\Repository\PromoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("api/admin/promo/{id}/profilsorties", name="affiche_apprenat_profil")
     */
    public function recupeApprenant(ProfilSortiRepository $repoPro, PromoRepository $promosRepository, GroupeRepository $repogroup, $id): \Symfony\Component\HttpFoundation\JsonResponse
    {


        $promo = $promosRepository->findOneBy(["id" => $id]);
        if (!$promo)
            return $this->json("le promo  avec id $id n'existe pas", Response::HTTP_BAD_REQUEST);

        $groupes = $promo->getGroupes();
        //dd($groupe);
        $grpApprenant = [];
        foreach ($groupes as $groupe) {
            foreach ($groupe->getApprenants() as $apprenant) {
                if ($apprenant->getProfilSorti()) {
                    $grpApprenant[] = $apprenant->getProfilSorti();
                }
            }
        }
        return $this->json($grpApprenant, Response::HTTP_OK);

    }


    /**
     * @Route("api/admin/promo/{id}/profilsortie/{num}", name="get_apprenat_profil")
     */
    public function getAppreantsByProfilSortiesInPromo(ProfilSortiRepository $repoPro, PromoRepository $promoRepository, $id, $num): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $promo = $promoRepository->find($id);
        $profil = $repoPro->find($num);
        if($promo && $profil){
            $profilSorties = [];
            foreach ($promo->getGroupes() as $groupe) {
                dd($promo->getGroupes());
                foreach ($groupe->getApprenants() as $apprenant){
                    foreach ($apprenant->getProfilSorti() as $ps) {
                        dd($ps);
                        if($ps->getId() == $num){$profilSorties = $profil;}
                    }
                }
            }

            return $this->json($profilSorties, 200);
        }else{
            return $this->json("le promo  ou le profil de sorti n'existe pas", Response::HTTP_BAD_REQUEST);
        }
    }
}
