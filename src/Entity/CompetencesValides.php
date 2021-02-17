<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CompetencesValidesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CompetencesValidesRepository::class)
 */
class CompetencesValides
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $niveau1;

    /**
     * @ORM\Column(type="boolean")
     */
    private $niveau2;

    /**
     * @ORM\Column(type="boolean")
     */
    private $niveau3;

    /**
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="competencesValides", cascade="persist")
     */
    private $competences;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="competencesValides", cascade="persist")
     */
    private $apprenants;

    /**
     * @ORM\ManyToOne(targetEntity=Referenciel::class, inversedBy="competencesValides", cascade="persist")
     */
    private $referenciels;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="competencevalides", cascade="persist")
     */
    private $promo;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNiveau1(): ?bool
    {
        return $this->niveau1;
    }

    public function setNiveau1(bool $niveau1): self
    {
        $this->niveau1 = $niveau1;

        return $this;
    }

    public function getNiveau2(): ?bool
    {
        return $this->niveau2;
    }

    public function setNiveau2(bool $niveau2): self
    {
        $this->niveau2 = $niveau2;

        return $this;
    }

    public function getNiveau3(): ?bool
    {
        return $this->niveau3;
    }

    public function setNiveau3(bool $niveau3): self
    {
        $this->niveau3 = $niveau3;

        return $this;
    }

    public function getCompetences(): ?Competence
    {
        return $this->competences;
    }

    public function setCompetences(?Competence $competences): self
    {
        $this->competences = $competences;

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

    public function getReferenciels(): ?Referenciel
    {
        return $this->referenciels;
    }

    public function setReferenciels(?Referenciel $referenciels): self
    {
        $this->referenciels = $referenciels;

        return $this;
    }

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

}
