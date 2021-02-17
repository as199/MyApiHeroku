<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeTagsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GroupeTagsRepository::class)
 *  @ApiResource(attributes={"pagination_items_per_page"=2},
 *     itemOperations={
 *             "get_grptags_id": {
 *             "method": "GET",
 *             "path": "/admin/grptags/{id}",
 *              "normalization_context"={"groups":"grptag:read"},
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *
 *         }
 *     ,"put_grptags_id": {
 *             "method": "PUT",
 *             "path": "/admin/grptags/{id}",
 *              "normalization_context"={"groups":"grptag:read"},
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *
 *         },
 *     "delete_grptags_id": {
 *             "method": "DELETE",
 *             "path": "/admin/grptags/{id}",
 *              "normalization_context"={"grptag":"formateur:read"},
 *              "access_control"="(is_granted('ROLE_ADMIN'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *
 *         }
 *     },
 *     collectionOperations={
 *       "get_grpstags": {
 *             "method": "GET",
 *             "path": "/admin/grptags",
 *              "normalization_context"={"grptag":"formateur:read"},
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *
 *         },
 *     "addGroupeTag":{
 *              "route_name"="addingGroupstags",
 *              "path": "/admin/grptags",
 *               "access_control"="(is_granted('ROLE_ADMIN') )",
 *               "deserialize" = false
 *              }
 *     })
 */
class GroupeTags
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"grptag:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"grptag:read"})
     */
    private $libelle;



    /**
     * @ORM\ManyToMany(targetEntity=Tags::class, inversedBy="groupeTags")
     */
    private $tags;

    /**
     * @ORM\Column(type="boolean")
     * @Groups ({"grptag:read"})
     */
    private $status = false;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * @return Collection|Tags[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tags $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }
}
