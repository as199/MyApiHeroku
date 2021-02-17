<?php

namespace App\Controller;

use App\Entity\GroupeTags;
use App\Entity\Tags;
use App\Repository\TagsRepository;
use App\Service\ValidatorPost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GroupeTagsController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serialser;
    /**
     * @var TagsRepository
     */
    private TagsRepository $tagsRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;
    /**
     * @var ValidatorPost
     */
    private ValidatorPost $validatorPost;

    /**
     * GroupeTagsController constructor.
     */
    public function __construct(SerializerInterface $serializer,TagsRepository $tagsRepository,EntityManagerInterface $manager,ValidatorPost $validatorPost)
    {
        $this->serialser =$serializer;
        $this->tagsRepository =$tagsRepository;
        $this->manager = $manager;
        $this->validatorPost = $validatorPost;
    }

    /**
     * @Route(
     *     "api/admin/grptags",
     *      name="addingGroupstags",
     *     methods={"POST"},
     *     defaults={
     *      "_api_resource_class"=GroupeTags::class,
     *      "_api_collection_operation_name"="addGroupeTag"
     *     }
     *     )
     */
    public function addGroupeTag(Request $request)
    {
        $compObject= $this->serialser->decode($request->getContent(),'json');
        //dd($compObject);
        $groupsTag = new GroupeTags();
        $groupsTag->setLibelle($compObject['libelle']);
        if($compObject['tags']){
            foreach ($compObject['tags'] as $tags){
                if($this->tagsRepository->findOneBy(["libelle"=>$tags['libelle']])){
                    $groupsTag->addTag($this->tagsRepository->findOneBy(["libelle"=>$tags['libelle']]));
                    $this->validatorPost->ValidatePost($groupsTag) ;
                    $this->manager->persist($groupsTag);
                }else{
                    $tag = new Tags();
                    $tag->setLibelle($tags['libelle'])
                        ->setDescriptif($tags['descriptif'])
                        ->setStatus(false);
                    $this->validatorPost->ValidatePost($tag) ;
                    $this->manager->persist($tag);
                    $this->validatorPost->ValidatePost($groupsTag) ;
                    $groupsTag->addTag($tag);
                    $this->manager->persist($groupsTag);

                }
            }

        }else{
            return new JsonResponse("Error please enter the tag association!",400,[],true);

        }
        $this->manager->flush();
        return $groupsTag;
    }

}
