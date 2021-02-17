<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\Promo;
use App\Repository\ApprenantRepository;
use App\Repository\ProfilRepository;
use App\Repository\PromoRepository;
use App\Repository\ReferencielRepository;
use App\Service\GestionImage;
use App\Service\PromoService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PromoController extends AbstractController
{
    /**
     * @var PromoRepository
     */
    private PromoRepository $promoReposity;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;
    /**
     * @var GestionImage
     */
    private GestionImage $gestionImage;
    /**
     * @var ApprenantRepository
     */
    private ApprenantRepository $apprenantReposity;
    /**
     * @var ProfilRepository
     */
    private ProfilRepository $profilRepository;
    /**
     * @var ReferencielRepository
     */
    private ReferencielRepository $referencielRepository;


    /**
     * PromoController constructor.
     */
    public function __construct(
        GestionImage $gestionImage,ReferencielRepository $referencielRepository,
        ProfilRepository $profilRepository, EntityManagerInterface $manager,PromoRepository $promoRepository,ApprenantRepository $apprenantReposity
    )
    {
        $this->gestionImage = $gestionImage;
        $this->manager = $manager;
        $this->promoReposity = $promoRepository;
        $this->apprenantReposity = $apprenantReposity;
        $this->profilRepository =$profilRepository;
        $this->referencielRepository =$referencielRepository;
    }
    

    public function DeletePromo($id): JsonResponse
    {
        

       if($myPromo = $this->promoReposity->find($id)){
            $myPromo->setStatus(true);
            $this->manager->persist($myPromo);
            $this->manager->flush();
            return new JsonResponse("success",200,[],true);
        }

    }


    public function EditPromo($id, Request $request,PromoService $promoService): JsonResponse
    {

        $promoreq = $request->request->all();

        $myPromo = $this->promoReposity->find($id);
        //dd($myPromo);
        foreach($myPromo->getApprenants() as  $key=> $value ){
            //dd($value);
            $myPromo->removeApprenant($value);
        }
        foreach($myPromo->getReferenciels() as  $key=> $value ){
            //dd($value);
            $myPromo->removeReferenciel($value);
        }


        if( $request->files->get('upload')){

            $file= $request->files->get('upload');
            $user = $promoService->uploadExcel($file);
            $users = $promoService->toArray($user);
            for ($p =0; $p < count($users); $p++){
                if ($users[$p]['email']){
                    if( $this->apprenantReposity->findOneBy(['email'=>$users[$p]['email']])){
                        $myPromo->addApprenant( $this->apprenantReposity->findOneBy(['email'=>$users[$p]['email']]));
                    }else{
                        $apprenant = new Apprenant();
                        $apprenant->setNomComplete('null')
                            ->setUsername($users[$p]['email'])
                            ->setPassword('passer123')
                            ->setAdresse('null')
                            ->setEmail($users[$p]['email'])
                            ->setGenre('null')
                            ->setProfil($this->profilRepository->findOneBy(['libelle'=>'Apprenant']))
                            ->setTelephone('');
                        $this->manager->persist($apprenant);
                        $myPromo->addApprenant($apprenant);
                    }
                }

            }

        }

        if( $request->files->get('avatar')){
            $avatar= $request->files->get('avatar');
            $avatar = fopen($avatar->getRealPath(),'r+');
            $myPromo->setAvatar($avatar);
        }
        foreach ($promoreq as $key=> $valeur){
            $setter = 'set'. ucfirst(strtolower($key));
            //dd($setter);
            if(method_exists(Promo::class, $setter)){
                if ($key=='dateFinProvisoire'){
                    try {
                        $dateFinP = new DateTime($promoreq['dateFinProvisoire']);
                        $myPromo->setDateFinProvisoire($dateFinP);
                    } catch (Exception $e) {

                    }
                }elseif ($key=='dateFinReel'){
                    try {
                        $dateFinR = new DateTime($promoreq['dateFinReel']);
                        $myPromo->setDateFinReel($dateFinR);

                    } catch (Exception $e) {

                    }
                }
                else{
                    $myPromo->$setter($valeur);

                }


            }

        }


        if(isset($promoreq['apprenants'])){
            $array = explode(',',$promoreq['apprenants']);
            //dd($array);
            for ($i=0;$i< count($array)-1;$i++){
                // dd($this->apprenantReposity->findOneBy(['email'=>$array[$i]]));
                if( $this->apprenantReposity->findOneBy(['email'=>$array[$i]])){

                    $myPromo->addApprenant( $this->apprenantReposity->findOneBy(['email'=>$array[$i]]));
                }
            }
        }
        if(isset($promoreq['referenciel'])){
            $array = explode(',',$promoreq['referenciel']);
            //dd($array);
            for ($i=0;$i< count($array)-1;$i++){
                // dd($this->apprenantReposity->findOneBy(['email'=>$array[$i]]));
                if( $this->referencielRepository->findOneBy(['id'=>$array[$i]])){

                    $myPromo->addReferenciel( $this->referencielRepository->findOneBy(['id'=>$array[$i]]));
                }
            }
        }

        $this->manager->persist($myPromo);
        $this->manager->flush();
        return new JsonResponse("success",200,[],true);
    }


    public function AddPromo(Request $request,PromoService $promoService): JsonResponse
    {
        $promoreq = $request->request->all();

        $myPromo = new Promo();
        if( $request->files->get('upload')){

            $file= $request->files->get('upload');
            $user = $promoService->uploadExcel($file);
            $users = $promoService->toArray($user);

            for ($p =0; $p < count($users); $p++){
                if ($users[$p]['email']){
                    if( $this->apprenantReposity->findOneBy(['email'=>$users[$p]['email']])){
                        $myPromo->addApprenant( $this->apprenantReposity->findOneBy(['email'=>$users[$p]['email']]));
                    }else{
                        $apprenant = new Apprenant();
                        $apprenant->setNomComplete('null')
                            ->setUsername($users[$p]['email'])
                            ->setPassword('passer123')
                            ->setAdresse('null')
                            ->setEmail($users[$p]['email'])
                            ->setGenre('null')
                            ->setProfil($this->profilRepository->findOneBy(['libelle'=>'Apprenant']))
                            ->setTelephone('');
                        $this->manager->persist($apprenant);
                        $myPromo->addApprenant($apprenant);
                    }
                }

            }

        }

        if( $request->files->get('avatar')){
            $avatar= $request->files->get('avatar');
            $avatar = fopen($avatar->getRealPath(),'r+');
            $myPromo->setAvatar($avatar);
        }
        foreach ($promoreq as $key=> $valeur){
            //dd('magui fi');
            $setter = 'set'. ucfirst(strtolower($key));
            if($setter == 'setApprenants'){
                $array = explode(',',$promoreq['apprenants']);
                //dd($array);
                for ($i=0;$i< count($array)-1;$i++){
                    if($apprenantExistant = $this->apprenantReposity->findOneBy(['email'=>$array[$i]])){

                        $myPromo->addApprenant($apprenantExistant);
                    }else{
                        $apprenant = new Apprenant();
                        $apprenant->setNomComplete($array[$i].'null')
                            ->setUsername($array[$i])
                            ->setPassword('passer123')
                            ->setAdresse('null')
                            ->setEmail($array[$i])
                            ->setGenre('null')
                            ->setProfil($this->profilRepository->findOneBy(['libelle'=>'Apprenant']))
                            ->setTelephone('');
                        $this->manager->persist($apprenant);
                        $myPromo->addApprenant($apprenant);
                    }
                }
            }elseif ($setter == 'setReferenciel'){
                $array = explode(',',$promoreq['referenciel']);
                for ($i=0;$i< count($array)-1;$i++){
                    if( $this->referencielRepository->findOneBy(['id'=>$array[$i]])){

                        $myPromo->addReferenciel( $this->referencielRepository->findOneBy(['id'=>$array[$i]]));
                    }
                }
            }
            //dd($setter);
            if(method_exists(Promo::class, $setter)){
                if ($key=='dateFinProvisoire'){
                    try {
                        $dateFinP = new DateTime($promoreq['dateFinProvisoire']);
                        $myPromo->setDateFinProvisoire($dateFinP);
                    } catch (\Exception $e) {

                    }
                }elseif ($key=='dateFinReel'){
                    try {
                        $dateFinR = new DateTime($promoreq['dateFinReel']);
                        $myPromo->setDateFinReel($dateFinR);

                    } catch (\Exception $e) {

                    }
                }
                else{
                    $myPromo->$setter($valeur);

                }


            }

        }

        $this->manager->persist($myPromo);
        $this->manager->flush();
        return new JsonResponse("success",200,[],true);

    }
}
