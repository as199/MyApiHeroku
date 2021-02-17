<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Service\GestionImage;
use App\Service\SendEmail;
use App\Service\ValidatorPost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApprenantController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;
    /**
     * @var SendEmail
     */
    private SendEmail $sendEmail;
    /**
     * @var ValidatorPost
     */
    private ValidatorPost $validation;

    /**
     * ApprenantController constructor.
     */
    public function __construct(EntityManagerInterface $manager, ValidatorPost $validation,SerializerInterface $serializer,SendEmail $sendEmail)
    {
        $this->manager=$manager;
        $this->serializer=$serializer;
        $this->sendEmail=$sendEmail;
        $this->validation = $validation;
    }

    /**
     * @Route(
     *     "api/apprenants",
     *      name="addApprenant",
     *     methods={"POST"},
     *     defaults={
     *      "_api_resource_class"=Apprenant::class,
     *      "_api_collection_operation_name"="addApprenant"
     *     }
     *     )
     */
    public function addApprenant(GestionImage $service, Request $request): JsonResponse
    {

        $utilisateur = $service->GestionImage("Apprenant",$request);
        //dd($utilisateur);
        if (!empty($this->validation->ValidatePost($utilisateur))){
            return $this->json($this->validation->ValidatePost($utilisateur),400);
        }
        $this->manager->persist($utilisateur);
        $this->manager->flush();
        $this->sendEmail->send($utilisateur['email'],"registration",'your registration has been successfully completed');

        return new JsonResponse("success",200,[],true);

    }
}
