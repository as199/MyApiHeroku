<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TagsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TagsRepository::class)
 * @ApiResource(attributes={"pagination_items_per_page"=2},
 *     collectionOperations={
 *     "get_tags": {
 *             "method": "GET",
 *             "path": "/admin/tags",
 *              "normalization_context"={"groups":"tag:read"},
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *
 *         },"post_tags": {
 *             "method": "POST",
 *             "path": "/admin/tags",
 *              "normalization_context"={"groups":"tag:read"},
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *
 *         }
 *     },itemOperations={
 *     "get_tags_id": {
 *             "method": "GET",
 *             "path": "/admin/tags/{id}",
 *              "normalization_context"={"groups":"tag:read"},
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *
 *         }
 *     ,"put_tags_id": {
 *             "method": "PUT",
 *             "path": "/admin/tags/{id}",
 *              "normalization_context"={"groups":"tag:read"},
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *
 *         }
 *
 *     })
 * @UniqueEntity ("libelle")
 */
class Tags
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"tag:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"tag:read"})
     * @Assert\NotBlank
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeTags::class, mappedBy="tags")
     */
    private $groupeTags;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"tag:read"})
     * @Assert\NotBlank (message="veuillez remplire ce champs")
     */
    private $descriptif;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status=false;

    /**
     * @ORM\ManyToMany(targetEntity=Brief::class, mappedBy="tags")
     */
    private $briefs;

    public function __construct()
    {
        $this->groupeTags = new ArrayCollection();
        $this->briefs = new ArrayCollection();
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
     * @return Collection|GroupeTags[]
     */
    public function getGroupeTags(): Collection
    {
        return $this->groupeTags;
    }

    public function addGroupeTag(GroupeTags $groupeTag): self
    {
        if (!$this->groupeTags->contains($groupeTag)) {
            $this->groupeTags[] = $groupeTag;
            $groupeTag->addTag($this);
        }

        return $this;
    }

    public function removeGroupeTag(GroupeTags $groupeTag): self
    {
        if ($this->groupeTags->removeElement($groupeTag)) {
            $groupeTag->removeTag($this);
        }

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

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

    /**
     * @return Collection|Brief[]
     */
    public function getBriefs(): Collection
    {
        return $this->briefs;
    }

    public function addBrief(Brief $brief): self
    {
        if (!$this->briefs->contains($brief)) {
            $this->briefs[] = $brief;
            $brief->addTag($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->removeElement($brief)) {
            $brief->removeTag($this);
        }

        return $this;
    }
}
