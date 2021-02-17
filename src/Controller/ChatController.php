<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Repository\ApprenantRepository;
use App\Repository\ChatRepository;
use App\Repository\PromoRepository;
use App\Repository\UtilisateurRepository;
use App\Service\ChatService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    /**
     * @var PromoRepository
     */
    private PromoRepository $promoRepository;
    /**
     * @var UtilisateurRepository
     */
    private UtilisateurRepository $userRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    /**
     * ChatController constructor.
     */
    public function __construct(EntityManagerInterface $manager,PromoRepository $promoRepository,UtilisateurRepository $userRepository)
    {
        $this->manager = $manager;
        $this->promoRepository =$promoRepository;
        $this->userRepository = $userRepository;
    }

    public function AddNewChat(Request $request, ChatService $chatService){
            $chat = $chatService->NewChat($request);
            //dd($chat["message"]);
            $nChat = new  Chat();
        foreach ($chat as $key=> $valeur) {
            $setter = 'set' . ucfirst(strtolower($key));
            if ($setter == "setPromo"){
                $promo = (int)$valeur;
                if ($this->promoRepository->find($promo)){
                    $nChat->setPromo($this->promoRepository->find($promo));
                }else{
                    return "veuiller verifie la promo";
                }
            }elseif ($setter == "setUsers"){
                $user = (int)$valeur;
                if($this->userRepository->find($user)){
                    $nChat->setUsers($this->userRepository->find($user));
                }
            }else{
                $nChat->$setter($valeur);
            }
        }
        $this->manager->persist($nChat);
        $this->manager->flush();
        return new JsonResponse("success",200,[],true);
        }

        public function gettingChat($id,$id1,PromoRepository $promoRepository,ApprenantRepository $apprenantRepository,ChatRepository $chatRepository): JsonResponse
        {
        $promoId = (int)$id;
        $userId = (int)$id1;
        $promo = $promoRepository->find($promoId);
        $user = $apprenantRepository->find($userId);
        if (!empty($promo) && !empty($user)){
            if ($user->getPromo()->getId() == $promoId){
                $chats = $chatRepository->findBy(['users'=>$userId]);
                //dd($chats);
            }else{
                return new JsonResponse("error promo or apprenant not found",500,[],true);
            }
        }else{
            return new JsonResponse("error promo or apprenant not found",500,[],true);
        }
            return $this->json($chats, Response::HTTP_OK, [], ['groups' => 'profil_sortis_get:read']);



        }
}
