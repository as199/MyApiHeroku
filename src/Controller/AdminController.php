<?php

namespace App\Controller;



use App\Entity\Utilisateur;
use App\Entity\Admin;
use App\Repository\ProfilRepository;
use App\Repository\UtilisateurRepository;
use App\Service\GestionImage;
use App\Service\InscriptionService;
use App\Service\SendEmail;
use App\Service\ValidatorPost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class AdminController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;
    /**
     * @var SerializerInterface
     */
    private  $serializer;
    /**
     * @var SendEmail
     */
    private SendEmail $sendEmail;
    private $encode;
    private $profilRepository;
    /**
     * @var ValidatorPost
     */
    private ValidatorPost $validator;


    /**
     * @Route("/home", name="app_home")
     */
    public function home()
    {

        return $this->render('admin/index.html.twig');
    }

    /**
     * AdminController constructor.
     */
    public function __construct(ProfilRepository $profilRepository,EntityManagerInterface $manager
        ,SerializerInterface $serializer,SendEmail $sendEmail,
          UserPasswordEncoderInterface $encode,ValidatorPost $validator)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->sendEmail = $sendEmail;
        $this->encode =$encode;
        $this->profilRepository =$profilRepository;
        $this->validator =$validator;
    }

    /**
     * @Route(
     *     "api/admin/users",
     *      name="adding",
     *     methods={"POST"},
     *     defaults={
     *      "_api_resource_class"=Utilisateur::class,
     *      "_api_collection_operation_name"="addUser"
     *     }
     *     )
     */
    public function AddUser(InscriptionService $service, Request $request)
    {
        $type = $request->get('type'); //pour dynamiser
       // dd($type);
        $utilisateur = $service->NewUser($type,$request);
       $this->validator->ValidatePost($utilisateur) ;


        $utilisateur->setStatus(false);
        $this->manager->persist($utilisateur);
       //dd($utilisateur);
         $this->manager->flush();
        $this->sendEmail->send($utilisateur->getEmail(),"registration",'your registration has been successfully completed');
       // $utilisateur->setAvartar($utilisateur->getAvartar());

        return $utilisateur;
    }

    /**
     * @Route(
     *     "api/admin/users/{id}",
     *      name="putUserId",
     *     methods={"PUT"},
     *     defaults={
     *      "_api_resource_class"=Utilisateur::class,
     *      "_api_item_operation_name"="putUserId"
     *     }
     *     )
     */
    public function putUserId(GestionImage $service, Request $request): JsonResponse
    {
        $profil = $request->get('profil'); //pour dynamiser
        //dd($profil);
        $userUpdate = $service->GestionImage($request,'avartar');
        $utilisateur = $request ->attributes->get('data');
       foreach ($userUpdate as $key=> $valeur){
           $setter = 'set'. ucfirst(strtolower($key));
           //dd($setter);
           if(method_exists(Utilisateur::class, $setter)){
               if($setter=='setProfil'){
                   $utilisateur->setProfil($userUpdate["profil"]);
               }
               else{
                   $utilisateur->$setter($valeur);
               }

           }
           if ($setter=='setPassword'){
               $utilisateur->setPassword($this->encode->encodePassword($utilisateur,$userUpdate['password']));

           }

       }
       $this->manager->persist($utilisateur);
       $this->manager->flush();
       return new JsonResponse("success",200,[],true);


    }
    
    /**
     * @Route(
     *     "api/admin/users/{id}",
     *      name="deleting",
     *     methods={"DELETE"},
     *     defaults={
     *      "_api_resource_class"=Utilisateur::class,
     *      "_api_item_operation_name"="deleteUserId"
     *     }
     *     )
     */
    public function DeleteUser($id, UtilisateurRepository $userRepository){
      if ( $user = $userRepository->findOneBy(['id'=>$id])) {
          $user->setStatus(true);
          $this->manager->persist($user);
          $this->manager->flush();
          return new JsonResponse("success", 200, [], true);
      }
      else{
              return new JsonResponse("error",400,[],true);
      }
    }


     /**
     * @Route(
     *     "api/admin/mailer",
     *      name="Sending",
     *     methods={"POST"},
     *     defaults={
     *      "_api_resource_class"=Admin::class,
     *      "_api_collection_operation_name"="SendEmailApp"
     *     }
     *     )
     */
    public function SendEmailApprenant( Request $request)
    {
      $lesMails=$request->get('email');
      $array = explode('&',$lesMails);
      //dd($array);
      for ($i=0;$i< count($array)-1;$i++){
        $this->sendEmail->send($array[$i],"Activation",'Please can your activate you compte');
      }
      return new JsonResponse("success",200,[],true);
       
       // $this->sendEmail->send($utilisateur->getEmail(),"registration",'your registration has been successfully completed');
       // $utilisateur->setAvartar($utilisateur->getAvartar());

       
    }
}
