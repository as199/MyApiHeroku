<?php


namespace App\dataProvider;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\PaginationExtension;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryResultCollectionExtensionInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Apprenant;
use App\Entity\Competence;
use App\Entity\GroupeCompetence;
use App\Entity\Referenciel;
use App\Entity\Utilisateur;
use App\Repository\ApprenantRepository;
use App\Repository\BriefMaPromoRepository;
use App\Repository\ChatRepository;
use App\Repository\FormateurRepository;
use App\Repository\GroupeRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\PromoRepository;
use App\Service\LivrablePartielService;
use App\Service\ProfilSortiService;
use App\Service\PromoService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;

class MollectionDataProvider  implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    /**
     * @var ManagerRegistry
     */
    private ManagerRegistry $manager;
    /**
     * @var PaginationExtension
     */
    private PaginationExtension $paginator;
    private $context;
    /**
     * @var PromoService
     */
    private PromoService $promoService;
    /**
     * @var LivrablePartielService
     */
    private LivrablePartielService $livrablePartielService;
    /**
     * @var ProfilSortiService
     */
    private ProfilSortiService $profilSortiService;
    /**
     * @var ApprenantRepository
     */
    private ApprenantRepository $apprenantRepository;
    /**
    *@var UtilisateurRepository
    */
    private UtilisateurRepository $userRepo;
    /**
     * @var ChatRepository
     */
    private ChatRepository $chatRepository;
    /**
     * @var PromoRepository
     */
    private PromoRepository $promoRepository;
    /**
     * @var GroupeRepository
     */
    private GroupeRepository $groupeRepository;
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;
    /**
     * @var FormateurRepository
     */
    private FormateurRepository $formateurRepository;
    /**
     * @var BriefMaPromoRepository
     */
    private BriefMaPromoRepository $briefMaPromoRepository;

    /**
     * MollectionDataProvider constructor.
     */
    public function __construct(SerializerInterface $serializer,ApprenantRepository $apprenantRepository,PromoRepository $promoRepository,
         PaginationExtension $paginator,ManagerRegistry $manager,ChatRepository $chatRepository,GroupeRepository $groupeRepository
        ,PromoService $promoService,LivrablePartielService $livrablePartielService,
          ProfilSortiService $profilSortiService, FormateurRepository $formateurRepository,
                                BriefMaPromoRepository $briefMaPromoRepository,
                                UtilisateurRepository $userRepo
                                )
    {
        $this->paginator = $paginator;
        $this->manager =$manager;
        $this->promoService = $promoService;
        $this->livrablePartielService = $livrablePartielService;
        $this->profilSortiService = $profilSortiService;
        $this->apprenantRepository = $apprenantRepository;
        $this->chatRepository = $chatRepository;
        $this->promoRepository = $promoRepository;
        $this->groupeRepository = $groupeRepository;
        $this->serializer = $serializer;
        $this->userRepo = $userRepo;
        $this->formateurRepository = $formateurRepository;
        $this->briefMaPromoRepository = $briefMaPromoRepository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        $this->context =$context;
        return $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): \Doctrine\ORM\QueryBuilder
    {
        if ($operationName == "get_groupe_principale"){
            $collection = $this->promoService->getPromoPrincipale();
        }
        if($operationName =="get_apprenant_attente"){
            $collection = $this->promoService->getApprenantAttente();
            if ($this->paginator instanceof QueryResultCollectionExtensionInterface
                && $this->paginator->supportsResult($resourceClass, $operationName, $this->context)) {

                return $this->paginator->getResult($collection, $resourceClass, $operationName, $this->context);
            }

            return $collection->getQuery()->getResult();
        }
        if ($operationName == "referenceApprenantAttente"){
            $collection = $this->promoService->getApprenantAttente();
        }
        if ($operationName == "promoformateur"){
            $collection = $this->promoService->getFormateur();
        }
        if ($operationName =="addLivrable"){
            //dd("ddfzrfer");
            $data = explode("/",$context["request_uri"]);
            $id =(int)$data[4];
            $id1 =(int)$data[6];
            $collection = $this->livrablePartielService->getReferencielApprenantCompetence($id,$id1);

        }
        if ($operationName == "apprenantProfilSortie"){
            $data = explode("/",$context["request_uri"]);
            dd($data);
            $id = (int)$data[4];
           // dd($id);
            $collection = $this->profilSortiService->getApprenanttProfilSorti($id);
            return $collection;
        }
        if ($operationName == "afficherAppProfilSorti"){
            $data = explode("/",$context["request_uri"]);
            $id =(int)$data[4];
            $id1 =(int)$data[6];
            $collection = $this->apprenantRepository->recuperApprenants($id,$id1);
            //dd($collection);
        }
        if ($operationName == "get_formateur_id_brief"){
            $data = explode("/",$context["request_uri"]);
            //dd($data);
            $id =(int)$data[3];
            $collection = $this->formateurRepository->recupBrief($id);
            //dd($infos);
        }
        if ($operationName =="get_formateur_id_brief_valide"){
            $data = explode("/",$context["request_uri"]);
            //dd($data);
            $id =(int)$data[3];
            $collection = $this->formateurRepository->recupBriefValide($id);
            //dd($collection);
        }
        if($operationName =='get_all_competences'){
            return $this->manager
                ->getRepository(Competence::class)->createQueryBuilder('item')
                ->where('item.status = :deleted')
                ->setParameter('deleted', false)
                ->getQuery()
                ->getResult();
           //return $this->manager->getRepository(Competence::class)->findAll();

        }

          if($operationName =='getApprenantAttente'){
             $collection = $this->manager
                ->getRepository(Apprenant::class)->createQueryBuilder('item')
                ->where('item.attente = :deleted')
                ->setParameter('deleted', true)
                ->getQuery()
                ->getResult();
                return $collection;
           //return $this->manager->getRepository(Competence::class)->findAll();

        }

        if($operationName =='get_all_groupe_competences'){

            return $this->manager
                ->getRepository(GroupeCompetence::class)->createQueryBuilder('item')
                ->where('item.status = :deleted')
                ->setParameter('deleted', false)
                ->getQuery()
                ->getResult();
//           return $this->manager->getRepository(GroupeCompetence::class)->findAll();

        }
        if($operationName =='getAllReferenciel'){
            return $this->manager
                ->getRepository(Referenciel::class)->createQueryBuilder('item')
                ->where('item.status = :deleted')
                ->setParameter('deleted', false)
                ->getQuery()
                ->getResult();
           // $this->manager->getRepository(Referenciel::class)->findAll();

        }
        if ($operationName == "get_formateur_id_brief_promo"){
            $data = explode("/",$context["request_uri"]);
            $id =(int)$data[4];
            $id1 =(int)$data[6];
            $collection = $this->briefMaPromoRepository->findOneBySomeField($id,$id1);
            //dd($data);
        }
        if ($operationName == "get_livrablepartiel1"){
            $data = explode("/",$context["request_uri"]);
            $id =(int)$data[4];
            $id1 =(int)$data[6];
            $collection = $this->manager->getRepository(Apprenant::class)
                ->createQueryBuilder('a')
                ->innerJoin('a.promo', 'p')
                ->andWhere('p.id = :idp')
                ->setParameter('idp',$id)
                ->innerJoin('p.referenciels','r')
                ->andWhere('r.id = :idR')
                ->setParameter('idR',$id1);

        }
        if($operationName === "get_user_profil"){
        $data = explode("/",$context["request_uri"]);
        $id =(int)$data[4];
        $res =  $this->userRepo->findUserByProfil($id);
        $collection = $this->manager->getRepository(User::class)
            ->createQueryBuilder('u')
            ->innerJoin('u.profil','p')
            ->andWhere('p.id =:id')
            ->setParameter('id',$id);



        }

             $repository = $this->manager
                ->getManagerForClass($resourceClass)
                ->getRepository($resourceClass)->createQueryBuilder('item')
                ->where('item.status = :deleted')
                ->setParameter('deleted', false);
           // $this->paginator->applyToCollection($repository, new QueryNameGenerator(), $resourceClass, $operationName, $this->context);

//            if ($this->paginator instanceof QueryResultCollectionExtensionInterface
//            && $this->paginator->supportsResult($resourceClass, $operationName, $this->context)) {
//
//            return $this->paginator->getResult($repository, $resourceClass, $operationName, $this->context);
//            }
             return $repository->getQuery()->getResult();
    }
}
