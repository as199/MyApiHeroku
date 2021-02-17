<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\FildeDiscussion;
use App\Entity\LivrablePartiel;
use App\Repository\ApprenantRepository;
use App\Repository\BriefMaPromoRepository;
use App\Repository\CompetencesValidesRepository;
use App\Repository\LivrablePartielRepository;
use App\Repository\PromoRepository;
use App\Repository\ReferencielRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class LivrablePartielController extends AbstractController
{

    public function RecupApprenantRef($id,CompetencesValidesRepository $competencesValidesRepository,PromoRepository $promoRepository,ReferencielRepository $reporef,$num): JsonResponse
    {
        $comp = $competencesValidesRepository->findBy(["promo"=>$id]);
        dd($comp[0]->getCompetences());
        $promo = $promoRepository->find($id);
        $referenciel = $reporef->find($num);
       // dd($referenciel);
        if(!$promo || !$referenciel){
            return $this->json("la promo ou le referenciel n'existe pas", Response::HTTP_OK,[]);
        }
        $promos =$referenciel->getPromos();
        dd($promos);
        $competenceValides =[];
        foreach ($promos as $promo){
            dd($promo);
            if ($promo->getId() == $id){
                foreach ($promo->getGroupes() as $groupe){
                    dd($promo->getGroupes());
                    foreach ($groupe->getApprenants()as $apprenant){
                        foreach ($apprenant->getCompetencesValides() as $competenceValide){
                            $competenceValides []= $competenceValide;
                        }
                    }
                }
            }
        }

        return $this->json($competenceValides, Response::HTTP_OK,[], ["groups" => "livrablepartiel:read"]);
    }
    /**
     * @Route("api/formateurs/promo/{id}/referentiel/{num}/statistiques/competences", name="recuperer_stat_referentiel")
     */
    public function RecupStat($id,PromoRepository $promoRepository,$num,ReferencielRepository  $reporef): JsonResponse
    {

        $promo = $promoRepository->find($id);

        $referenciel = $reporef->find($num);
        if(!$promo || !$referenciel){
            return $this->json("Le promo ou le referenciel n'existe pas", Response::HTTP_OK,[]);
        }
        foreach ($promo->getReferenciels() as $referenciel) {
            if($referenciel->getId() == $num){
                foreach( $referenciel->getGroupeCompetences() as $groupeCompetence){
                    foreach ($groupeCompetence->getCompetences() as $competence ){
                        $app1 = 0;
                        $app2 = 0;
                        $app3 = 0;
                        foreach ($competence->getCompetenceValides() as $competenceValide){
                            if ($competenceValide->getNiveau1() == true){
                                $nb1 += 1;
                            }
                            if ($competenceValide->getNiveau2() == true){
                                $nb2 += 1;
                            }
                            if ($competenceValide->getNiveau3() == true){
                                $nb3 += 1;
                            }
                        }
                        $tab[] = ["competence"=>$competence,"niveau 1"=>$app1,"niveau 2"=>$app2,"niveau 3"=>$app3];
                    }
                }
            }
        }


        return $this->json($tab,Response::HTTP_OK,[], ["groups" => "livrablepartiel_stat:read"]);
    }


    /**
     * @Route(
     *     name="post_formateur",
     *     path="/api/formateurs/livrablepartiels/{id}/commentaires",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\LivrablePartielController::postAjoutFilDu",
     *          "__api_resource_class"=LivrablePartiel::class,
     *          "__api_collection_operation_name"="post_formateur"
     *     }
     *
     * )
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param TokenStorageInterface $token
     * @param LivrablePartielRepository $livrablePartielRepository
     * @param int $id
     * @return JsonResponse
     */
    public function  postAjoutFilDu(EntityManagerInterface $manager, Request $request, TokenStorageInterface $token, LivrablePartielRepository $livrablePartielRepository, int $id): JsonResponse
    {
        $discussion= json_decode($request->getContent(),true);

        if (!isset($discussion['commentaire'])){
            return new JsonResponse("Ajouter des commentaires au fil de discussion", Response::HTTP_BAD_REQUEST, [], true);
        }
        $livpar = $livrablePartielRepository->findOneBY(["id"=>$id]);

       // $user= $token->getToken()->getUser();
        $user = $token->getToken();
            dd($user);
        if (!$livpar) {
            return new JsonResponse("Le livrable partiel dont l'id=" . $id . "n'existe pas", Response::HTTP_BAD_REQUEST, [], true);

        }
        // foreach ($livpar->getApprenantLivrablePartiels() as $key => $appreliv) {

        //if ($user instanceof Formateur) {
        // $filDeDisscussion= $appreliv->getFilDeDiscussion();
        $fil= new FildeDiscussion();
        if (isset($discussion['titreFil'])){

            $fil->setTitre($discussion['titreFil']);
            //$fil->setDate(new  \DateTime());

        }

        foreach ($discussion['commentaire'] as $commentaire){

            $com= new Commentaire();
            $com->setDescription($commentaire['description'])
                ->setCreatedAt(new DateTime())
                ->setFilDeDiscussion($fil);
            if ($user instanceof Formateur){
                $com->setFormateurs($user);
            }
            $manager->persist($com);
        }
        $manager->persist($fil);

        //}
        // }
        $manager->flush();
        return new JsonResponse("Fil de discussion et commentaires ajoutés", Response::HTTP_BAD_REQUEST, [], true);
    }


    /**
     * @Route(
     *     name="post_apprenant",
     *     path="/api/apprenants/livrablepartiels/{id}/commentaires",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\LivrablePartielController::postAjoutFilDu",
     *          "__api_resource_class"=LivrablePartiel::class,
     *          "__api_collection_operation_name"="post_apprenant"
     *     }
     * )
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param TokenStorageInterface $token
     * @param LivrablePartielRepository $livrablePartielRepository
     * @param int $id
     * @return JsonResponse
     */
    public function  postAjoutFilDuApp(EntityManagerInterface $manager, Request $request, TokenStorageInterface $token, LivrablePartielRepository $livrablePartielRepository, int $id): JsonResponse
    {
        $discussion= json_decode($request->getContent(),true);

        if (!isset($discussion['commentaire'])){
            return new JsonResponse("Ajouter des commentaires au fil de discussion", Response::HTTP_BAD_REQUEST, [], true);
        }
        $livpar = $livrablePartielRepository->findOneBY(["id"=>$id]);

        //$user=$token->getToken()->getUser();

        $user   = $token->getToken();
        if (!$livpar) {
            return new JsonResponse("Le livrable partiel dont l'id=" . $id . "n'existe pas", Response::HTTP_BAD_REQUEST, [], true);

        }
        // foreach ($livpar->getApprenantLivrablePartiels() as $key => $appreliv) {

        //if ($user instanceof Formateur) {
        // $filDeDisscussion= $appreliv->getFilDeDiscussion();
        $fil= new FilDeDiscussion();
        if (isset($discussion['titreFil'])){

            $fil->setTitre($discussion['titreFil']);
            //$fil->setDate(new  \DateTime());

        }

        foreach ($discussion['commentaire'] as $commentaire){

            $com= new Commentaire();
            $com->setDescription($commentaire['description'])
                ->setCreatedAt(new DateTime())
                ->setFilDeDiscussion($fil);
            if ($user instanceof Apprenant){
                //$com->setApprenant($user);
            }
            $manager->persist($com);
        }
        $manager->persist($fil);

        //}
        // }
        $manager->flush();
        return new JsonResponse("Fil de discussion et commentaires ajoutés", Response::HTTP_BAD_REQUEST, [], true);
    }


    /**
     * @Route("/api/formateurs/livrablepartiels/{id}/commentaires", name="recuperer_les_commentaires", methods={"GET"})
     */
    public function LivrableComm($id, LivrablePartielRepository $partielRepository): JsonResponse
    {
        $livrable = $partielRepository->findOneBy(
            [
                "id" => $id
            ]
        );
        if (!$livrable) {
            return new JsonResponse("Le livrable partiel dont l'id=" . $id . "n'existe pas", Response::HTTP_BAD_REQUEST, [], true);
        }
        $TabCommentaires = [];

        foreach ($livrable->getApprenantLivrablePartiels() as $partiel) {

            foreach ($partiel->getFilDeDiscussions()->getCommentaires() as $commentaire) {
                $TabCommentaires[] = $commentaire;

            }
        }
        return $this->json(["Commentaires"=>$TabCommentaires],200,[],["groups"=>"commentaires:read"]);
    }


    /**
     * @Route(
     *   name="apprenant_competences",
     *   path="api/apprenant/{id}/promo/{idp}/referentiel/{idr}/competences",
     *   methods={"GET"}
     * )
     * @param CompetencesValidesRepository $competenceValid
     * @param SerializerInterface $serializer
     * @param $id
     * @param $idp
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */

    public function ReferentielIdCompetences(CompetencesValidesRepository $competenceValid,
                                             SerializerInterface $serializer,
                                             $id,
                                             $idp): JsonResponse
    {
        $Competences = $competenceValid->findAll();
        $tab=[];
        foreach ($Competences as  $Competence){
            $promo =  $Competence->getPromos();
            $apprenant =  $Competence->getApprenants();
            if(($promo->getId() == $idp) && ($apprenant->getId() == $id)){
                $competence =  $Competence->getCompetences();
                $competenceTab = $serializer->normalize($competence,'json',["groups"=>"competence:read"]);
                $tab[] = ["competence"=>$competenceTab];
            }
        }
        // dd($tab);
        return $this->json($tab,200,[]);
    }
    /**
     * @Route(
     *     name="get_deux_id",
     *     path="/api/formateurs/promo/{id}/brief/{id_l}/livrablepartiels",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\LivrablePartielController::getFormLiv",
     *          "__api_resource_class"=LivrablePartiel::class,
     *          "__api_item_operation_name"="get_deux_id"
     *     }
     * )
     */
    public function editLivrablePartielByFormateur(Request $request, $id, BriefMaPromoRepository $repoBrief,EntityManagerInterface $em): JsonResponse
    {

        $json = json_decode($request->getContent());
        $briefMapromo = $repoBrief->find($id);
        if ($briefMapromo) {
            $livrablePartiel = $this->getDoctrine()->getRepository(LivrablePartiel::class)->find($id);
            $livrablePartiel->setDelai(new DateTime())
                ->setLibelle($json->libelle)
                ->setDescription($json->description)
                ->setNombreRendu($json->nbreRendue)
                ->setNombreCorrige($json->nbreCorrige)
                ->setType($json->type)
                ->setBriefMaPromo($briefMapromo[0]);
            $em->flush();
            return $this->json("Ajout ou suppression réussi");
        } else {
            return $this->json("brief ou promo inexistant");
        }
    }
    /**
     * @Route(
     *     name="get_deux",
     *     path="/api/apprenants/{id}/livrablepartiels/{id_d}",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\LivrablePartielController::putAppLiv",
     *          "__api_resource_class"=LivrablePartiel::class,
     *          "__api_item_operation_name"="get_deux_it"
     *     }
     * )
     */

    public function putAppLiv(Request $request, EntityManagerInterface $manager, ApprenantRepository $apprenantRepository, LivrablePartielRepository $livrablePartielRepository, int $id, int $id_d)
    {

        $etatTab= json_decode($request->getContent(),true);

        $apprenant = $apprenantRepository->findOneBY(["id" => $id]);
        $livrapartiel = $livrablePartielRepository->findoneBY(["id" => $id_d]);

        if (!$apprenant) {
            return new JsonResponse("L'apprenant dont l'id=" . $id . "n'existe pas", Response::HTTP_CREATED, [], true);

        }
        if (!$livrapartiel) {
            return new JsonResponse("Le livrable dont l'id=" . $id_d . "n'existe pas", Response::HTTP_CREATED, [], true);

        }
        foreach ($apprenant->getApprenantLivrablePartiels() as $apl) {
            if ($apl->getLivrablePartiels()->getId()== $id_d){
                $apl->setEtat($etatTab['etat']);

            }

        }
        $manager->flush();
        return $this->json("Modification reussi");

    }

    /**
     * @Route("/api/apprenants/{id}/promo/{idp}/referentiel/{idr}/statistiques/briefs", name="apprenant_stat_briefs")
     * @param ApprenantRepository $appRepo
     * @param $id
     * @param $idp
     * @return JsonResponse
     */

    public function getApprenantBriefs(ApprenantRepository $appRepo,
                                       $id,
                                       $idp): JsonResponse
    {
        $apprenant = $appRepo->find($id);
        $groupes = $apprenant->getGroupes();
        foreach ($groupes as $groupe){
            $nbreAssigne=0;$nbreValid=0;$nbreNonValid=0;
            if($groupe->getStatus()== $id){
                if ($groupe->getPromo()->getId() == $idp){
                    $briefs = $groupe->getEtatBriefs();
                    foreach ($briefs as $brief){
                        $statut = $brief->getEtat();
                        if ($statut === "valide"){
                            $nbreValid +=1;
                        }elseif ($statut ==="non valide"){
                            $nbreNonValid +=1;
                        }else{
                            $nbreAssigne +=1;
                        }
                    }
                }
            }
            $tab [] =["Apprenant"=>$apprenant,"Valide"=>$nbreValid,"Non Valide"=>$nbreNonValid,"Assigne"=>$nbreAssigne];
        }
        // dd($tab);
        return $this->json($tab,200,[],['groups'=>'brief_read']);
    }
}
