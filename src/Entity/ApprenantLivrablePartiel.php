<?php

namespace App\Entity;

use App\Repository\ApprenantLivrablePartielRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApprenantLivrablePartielRepository::class)
 */
class ApprenantLivrablePartiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    /**
     * @ORM\Column(type="date")
     */
    private $delai;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="apprenantLivrablePartiels")
     */
    private $apprenants;

    /**
     * @ORM\ManyToOne(targetEntity=LivrablePartiel::class, inversedBy="apprenantLivrablePartiels")
     */
    private $livrablePartiels;

    /**
     * @ORM\OneToOne(targetEntity=FildeDiscussion::class, cascade={"persist", "remove"})
     */
    private $fildeDiscussion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDelai(): ?\DateTimeInterface
    {
        return $this->delai;
    }

    public function setDelai(\DateTimeInterface $delai): self
    {
        $this->delai = $delai;

        return $this;
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

    public function getLivrablePartiels(): ?LivrablePartiel
    {
        return $this->livrablePartiels;
    }

    public function setLivrablePartiels(?LivrablePartiel $livrablePartiels): self
    {
        $this->livrablePartiels = $livrablePartiels;

        return $this;
    }

    public function getFildeDiscussion(): ?FildeDiscussion
    {
        return $this->fildeDiscussion;
    }

    public function setFildeDiscussion(?FildeDiscussion $fildeDiscussion): self
    {
        $this->fildeDiscussion = $fildeDiscussion;

        return $this;
    }
}
