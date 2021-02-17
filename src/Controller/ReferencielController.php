<?php

namespace App\Controller;

use App\Entity\Referenciel;
use App\Repository\GroupeCompetenceRepository;
use App\Repository\ReferencielRepository;
use App\Service\GestionImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ReferencielController extends AbstractController
{
    /**
     * adding brief
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param GroupeCompetenceRepository $groupeC
     * @return JsonResponse
     */
   public function AddReferenciel(Request $request, EntityManagerInterface $manager, GroupeCompetenceRepository $groupeC): JsonResponse
   {

       $referenciel = $request->request->all();
       $myRef = new Referenciel();
       $myRef->setLibelle($referenciel['libelle']);
       $myRef->setPresentation($referenciel['presentation']);
       $myRef->setCriteradmission($referenciel['criteradmission']);
       $myRef->setCritereEvaluation($referenciel['critereEvaluation']);
       $myRef->setStatus(false);

       if( $request->files->get('programmes')){
           $programme= $request->files->get('programmes');
           $programme = fopen($programme,'rb');
           $myRef->setProgrammes($programme);
       }

       if($referenciel['groupeCompetence']){
           $array = explode(',',$referenciel['groupeCompetence']);
           for ($i=0;$i< count($array)-1;$i++){
               if($groupeC->findOneBy(['id'=>(int)$array[$i]])){
                   $myRef->addGroupeCompetence($groupeC->findOneBy(['id'=>(int)$array[$i]]));
               }
           }
       }

       $manager->persist($myRef);
       $manager->flush();
       return new JsonResponse("success",200,[],true);

   }

    public function EditReferenciel(Request $request,$id,ReferencielRepository $referencielRepository, GroupeCompetenceRepository $groupeC,GestionImage $service,EntityManagerInterface $manager): JsonResponse
    {
        //dd($id);
        $userUpdate = $service->GestionImage($request,'programmes');
        //dd($userUpdate);
        $utilisateur = $referencielRepository->find($id);
        foreach($utilisateur->getGroupeCompetence() as  $key=> $value ){
            //dd($value);
            $utilisateur->removeGroupeCompetence($value);
        }
        //dd($utilisateur);
        foreach ($userUpdate as $key=> $valeur){
            $setter = 'set'. ucfirst(strtolower($key));
            if($key == "groupeCompetence"){
                $array = explode(',',$valeur);
                for ($i=0;$i< count($array)-1;$i++){
                    if($groupeC->findOneBy(['id'=>(int)$array[$i]])){
                        $utilisateur->addGroupeCompetence($groupeC->findOneBy(['id'=>(int)$array[$i]]));
                    }
                }
            }
            //dd($setter);
            if(method_exists(Referenciel::class, $setter)){

                $utilisateur->$setter($valeur);
            }


        }
        $manager->persist($utilisateur);
        $manager->flush();
        return new JsonResponse("success",200,[],true);

    }
    public function DeleteReferenciel($id, ReferencielRepository $referencielRepository,EntityManagerInterface $manager)
    {
        if ($ref = $referencielRepository->find($id)){
            $ref->setStatus(true);
            $manager->persist($ref);
            $manager->flush();
            return new JsonResponse("success",200,[],true);
        }else{
            return new JsonResponse("referenciel not find",500,[],true);
        }
    }
}
