<?php

namespace App\Entity;

use App\Repository\LivrableAttenduApprenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LivrableAttenduApprenantRepository::class)
 */
class LivrableAttenduApprenant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="livrableAttenduApprenants")
     */
    private $apprenants;

    /**
     * @ORM\ManyToMany(targetEntity=LivrableAttendu::class, mappedBy="livrableattenduApprenants")
     */
    private $livrableAttendus;

    public function __construct()
    {
        $this->livrableAttendus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApprenants(): ?Apprenant
    {
        return $this->apprenants;
    }

    public function setApprenants(?Apprenant $apprenants): self
    {
        $this->apprenants = $apprenants;

        return $this;
    }

    /**
     * @return Collection|LivrableAttendu[]
     */
    public function getLivrableAttendus(): Collection
    {
        return $this->livrableAttendus;
    }

    public function addLivrableAttendu(LivrableAttendu $livrableAttendu): self
    {
        if (!$this->livrableAttendus->contains($livrableAttendu)) {
            $this->livrableAttendus[] = $livrableAttendu;
            $livrableAttendu->addLivrableattenduApprenant($this);
        }

        return $this;
    }

    public function removeLivrableAttendu(LivrableAttendu $livrableAttendu): self
    {
        if ($this->livrableAttendus->removeElement($livrableAttendu)) {
            $livrableAttendu->removeLivrableattenduApprenant($this);
        }

        return $this;
    }
}
