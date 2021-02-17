<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use App\Repository\GroupeRepository;
use App\Repository\PromoRepository;
use App\Service\ValidatorPost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GroupeController extends AbstractController
{
    /**
     * @var ApprenantRepository
     */
    private ApprenantRepository $apprenantRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;
    /**
     * @var FormateurRepository
     */
    private FormateurRepository $formateurRepository;
    /**
     * @var PromoRepository
     */
    private PromoRepository $promoRepository;
    /**
     * @var ValidatorPost
     */
    private ValidatorPost $validator;
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;
    /**
     * @var GroupeRepository
     */
    private GroupeRepository $groupeRepository;

    /**
     * GroupeController constructor.
     */
    public function __construct(EntityManagerInterface $manager,
                                ApprenantRepository $apprenantRepository,
                                FormateurRepository $formateurRepository,
                                PromoRepository $promoRepository,
                                ValidatorPost $validator,
                                SerializerInterface $serializer,
                                GroupeRepository $groupeRepository
    )
    {
        $this->manager =$manager;
        $this->apprenantRepository = $apprenantRepository;
        $this->formateurRepository = $formateurRepository;
        $this->promoRepository = $promoRepository;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->groupeRepository = $groupeRepository;
    }

    public function addGroupes(Request $request)
    {
        $json = json_decode($request->getContent(),true);

        $groupe = new Groupe();
        $groupe->setNomGroupe($json['nomGroupe'])
            ->setDateCreation(new \DateTime())
            ->setStatus($json['status']);
        //add groupe in promos
        ;
        if (isset($json['promo'])) {
            $promo = $this->promoRepository->find($json['promo']);
           //dd($promo);
            $groupe->addPromo($promo);
        }
        if (isset($json['apprenants'])) {
            for ($i= 0; $i < count($json['apprenants']); $i++) {
                $apprenant = $this->apprenantRepository->find($json['apprenants'][$i]['id']);
                $groupe->addApprenant($apprenant);
            }
        }
        if (isset($json['formateurs'])) {
            for ($i= 0; $i < count($json['formateurs']); $i++) {
                $formateur = $this->formateurRepository->find($json['formateurs'][$i]['id']);
                $groupe->addFormateur($formateur);
            }
        }
        $errors = $this->validator->ValidatePost($groupe);
        if ($errors) {
            return $this->json($errors,Response::HTTP_BAD_REQUEST);
        }
        $this->manager->persist($groupe);
        $this->manager->flush();
        return $this->json("success",Response::HTTP_CREATED);

    }

    public function putGroupes(Request $request,$id)
    {
        $json = json_decode($request->getContent(),true);
        $groupe = $this->groupeRepository->find($id);
        if ($groupe && isset($json['apprenants'])) {
            foreach ($json['apprenants'] as $apprenants){
                $apprenant = $this->apprenantRepository ->find($apprenants['id']);
                $groupe->addApprenant($apprenant);
            }
        }
        $this->manager->flush();
        return $this->json('added succesfully');
    }

    public function retireApprenantGroupe($idA,$idB)
    {
        $groupe = $this->groupeRepository->find($idA);
        $apprenant = $this->apprenantRepository->find($idB);
        if ($groupe && $apprenant) {
            $groupe->removeApprenant($apprenant);
            $this->manager->flush();
            return $this->json('remove succesfully');
        }
        return $this->json('groupe ou apprenant inexistant');
    }


}
