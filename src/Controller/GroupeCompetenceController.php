<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Entity\GroupeCompetence;
use App\Entity\Niveau;
use App\Repository\CompetenceRepository;
use App\Repository\GroupeCompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GroupeCompetenceController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;
    /**
     * @var GroupeCompetenceRepository
     */
    private GroupeCompetenceRepository $grpecompetences;
    /**
     * @var CompetenceRepository
     */
    private CompetenceRepository $competenceRepository;
    /**
     * @var Request
     */
    private Request $request;
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * GroupeCompetenceController constructor.
     */
    public function __construct(EntityManagerInterface $manager,GroupeCompetenceRepository $grpecompetences
        ,CompetenceRepository $competenceRepository,SerializerInterface $serializer)
    {
        $this->manager = $manager;
        $this->grpecompetences =$grpecompetences;
        $this->competenceRepository = $competenceRepository;
        $this->serializer = $serializer;
    }
    
        
    /**
     * @Route(
     *     "/api/admin/grpecompetences/{id}",
     *      name="deleteGroupcompetence",
     *     methods={"DELETE",},
     *     defaults={
     *      "_api_resource_class"=GroupeCompetence::class,
     *      "_api_item_operation_name"="deleteGrpCompetenceId"
     *     }
     *     )
     */
    public function deleteGrpCompetenceId($id){
          if( $groupe =$this->grpecompetences->findOneBy(['id'=>$id])){
             $groupe->setStatus(true);
             $this->manager->persist($groupe);
             $this->manager->flush();
             return new JsonResponse("success", 200, [], true);
          }
          else{
              return new JsonResponse("error",400,[],true);
          }
    }
        

    /**
     * @Route(
     *     "/api/admin/grpecompetences/{id}",
     *      name="putGroupcompetence",
     *     methods={"PUT",},
     *     defaults={
     *      "_api_resource_class"=GroupeCompetence::class,
     *      "_api_item_operation_name"="updateCompetenceId"
     *     }
     *     )
     */
    public function updateCompetenceId(int $id,  Request $request )
    {
        //dd("eeeeeeeeeeeeeee");
        $groupecompetence= $this->grpecompetences->find($id);
        //dd($groupecompetence);
        $compObject= json_decode($request->getContent());
        $groupecompetence->setLibelle($compObject->libelle);
        $groupecompetence->setDescriptif($compObject->descriptif);
        //dd($compObject);
        // on ecrase les competence existant
        if ($competencees = $groupecompetence->getCompetences()){
            foreach($competencees as $compo){
                $groupecompetence->removeCompetence($compo);

            }
        }

        if($compObject->option == "add")
        {//opticlan center sicap liberti 1 villa no 1109

            for ($i=0;$i<count($compObject->competences); $i++)
            {
                //dd($compObject->competences);
                if (isset($compObject->competences[$i]->id)){
                    $id =$compObject->competences[$i]->id;

                    $comp = $this->competenceRepository->findBy(['id'=>$id]);
                    //dd($comp);
                    $groupecompetence->addCompetence($comp[0]);
                    //dd($groupecompetence);
                }else{

                    if (isset($compObject->competences[$i]->niveau) && isset($compObject->competences[$i]->libelle)){
                        // dd('je contiens des niveaux');
                        $competence = new Competence();

                        $competence->setLibelle($compObject->competences[$i]->libelle);

                        if(count($compObject->competences[$i]->niveau) == 3 ){
                            foreach( $compObject->competences[$i]->niveau as $item){
                                // dd($item);
                                $niveau = new Niveau();
                                $niveau->setLibelle($item->libelle);
                                $niveau->setCritereEvalution($item->critereEvalution);
                                $niveau->setGroupeAction($item->groupeAction);
                                $niveau->setStatus(false);
                                $this->manager->persist($niveau);
                                $competence->addNiveau($niveau);
                            }
                        }
                        else{
                            return new JsonResponse("Error le nombre de niveau doit etre egal à 3",500,[],true);
                        }
                        $this->manager->persist($competence);
                        $groupecompetence->addCompetence($competence);

                    }else{
                        return new JsonResponse("Error renseigner tous les champs du competence",500,[],true);
                    }



                }

                $this->manager->flush();
                return $this->json('success');
            }



        }else{
            if ($compObject->competences){
                for ($i=0;$i<count($compObject->competences); $i++)
                {
                    if (isset($compObject->competences[$i]->id)){
                        if($newCompetence= $this->competenceRepository->findBy(['id'=> $compObject->competences[$i]->id])){
                            $groupecompetence->addCompetence($newCompetence[0]);
                            $this->manager->persist($groupecompetence);
                        }else{
                            return new JsonResponse("Error cette competence n'existe pas",500,[],true);
                        }


                    }
                }

            }else{
                return new JsonResponse("Error cette competence n'existe pas",500,[],true);
            }

        }
        $this->manager->flush();
        return $this->json("edit");


    }

    /**
     * @Route(
     *     "/api/admin/grpecompetences",
     *      name="groupcompadd",
     *     methods={"POST",},
     *     defaults={
     *      "_api_resource_class"=GroupeCompetence::class,
     *      "_api_collection_operation_name"="AddGroupeCompetences"
     *     }
     *     )
     */
    public function AddGroupeCompetences( Request $request ){

        $compObject= $this->serializer->decode($request->getContent(),'json');

        //dd($compObject);
        $groupeCompetene = new GroupeCompetence();
        $groupeCompetene->setLibelle($compObject['libelle']);
        $groupeCompetene->setDescriptif($compObject['descriptif']);
        $groupeCompetene->setStatus(false);
        foreach ($compObject['competence'] as $competence){
            // dd($competence);
            if($this->competenceRepository->findOneBy(['libelle'=>$competence['libelle']])){
                $objCompetence =$this->competenceRepository->findOneBy(['libelle'=>$competence['libelle']]);

                $groupeCompetene->addCompetence($objCompetence);
                $this->manager->persist($groupeCompetene);
            }else{
                if (isset($compObject['competence'][0]['niveau'])){
                    $data = $compObject['competence'][0]['niveau'];
                    //dd(count($data));
                    if (count($data) == 3){
                        foreach ($compObject['competence'] as $objetCompetence){
                            $competence = new Competence();
                            $competence->setLibelle($objetCompetence['libelle']);

                            foreach ($data as $objetniveau){
                                //dd($objetniveau);
                                $niveau = new Niveau();
                                $niveau->setLibelle($objetniveau['libelle'])
                                    ->setCritereEvalution($objetniveau['critereEvalution'])
                                    ->setGroupeAction($objetniveau['groupeAction']);
                                $competence->addNiveau($niveau);
                                $this->manager->persist($niveau);

                            }
                            $this->manager->persist($competence);
                            $groupeCompetene->addCompetence($competence);
                            $this->manager->persist($groupeCompetene);
                        }
                    }else{
                        return new JsonResponse("Error please enter 3 levels!",400,[],true);

                    }
                }else{
                    return new JsonResponse("Error please enter 3 levels!",400,[],true);
                }
            }
            $this->manager->flush();
        }
        return $this->json("valider");

    }
    
     


}
