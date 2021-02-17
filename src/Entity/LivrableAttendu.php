<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LivrableAttenduRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LivrableAttenduRepository::class)
 * @ApiResource ()
 */
class LivrableAttendu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"brief:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"brief:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"brief:read"})
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Brief::class, inversedBy="livrableAttendus")
     */
    private $briefs;

    /**
     * @ORM\ManyToMany(targetEntity=LivrableAttenduApprenant::class, inversedBy="livrableAttendus")
     */
    private $livrableattenduApprenants;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    public function __construct()
    {
        $this->briefs = new ArrayCollection();
        $this->livrableattenduApprenants = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        $this->briefs->removeElement($brief);

        return $this;
    }

    /**
     * @return Collection|LivrableAttenduApprenant[]
     */
    public function getLivrableattenduApprenants(): Collection
    {
        return $this->livrableattenduApprenants;
    }

    public function addLivrableattenduApprenant(LivrableAttenduApprenant $livrableattenduApprenant): self
    {
        if (!$this->livrableattenduApprenants->contains($livrableattenduApprenant)) {
            $this->livrableattenduApprenants[] = $livrableattenduApprenant;
        }

        return $this;
    }

    public function removeLivrableattenduApprenant(LivrableAttenduApprenant $livrableattenduApprenant): self
    {
        $this->livrableattenduApprenants->removeElement($livrableattenduApprenant);

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
