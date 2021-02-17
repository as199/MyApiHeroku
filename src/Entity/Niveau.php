<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NiveauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=NiveauRepository::class)
 * @UniqueEntity ("libelle")
 * @ApiResource ()
 */
class Niveau
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"brief:read","competence:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="please enter your libelle")
     * @Groups ({"brief:read","gcuadd:write","gprecompetence:write","competence:write","competence:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="please enter your evaluation critere")
     * @Groups ({"brief:read","gprecompetence:write","gcuadd:write","competence:write","competence:read"})
     */
    private $critereEvalution;

    /**
     * @ORM\Column(type="string", length=255)
     *@Assert\NotBlank(message="please enter your action groups")
     * @Groups ({"competence:write","gprecompetence:write","gcuadd:write","competence:read"})
     */
    private $groupeAction;

    /**
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="niveau")
     * @Groups ({"brief:read"})
     */
    private $competence;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\ManyToMany(targetEntity=Brief::class, mappedBy="niveaux")
     */
    private $briefs;

    /**
     * @ORM\ManyToMany(targetEntity=LivrablePartiel::class, mappedBy="niveau")
     */
    private $livrablePartiels;

    /**
     * Niveau constructor.
     * @param $status
     */
    public function __construct()
    {
        $this->status = false;
        $this->briefs = new ArrayCollection();
        $this->livrablePartiels = new ArrayCollection();
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

    public function getCritereEvalution(): ?string
    {
        return $this->critereEvalution;
    }

    public function setCritereEvalution(string $critereEvalution): self
    {
        $this->critereEvalution = $critereEvalution;

        return $this;
    }

    public function getGroupeAction(): ?string
    {
        return $this->groupeAction;
    }

    public function setGroupeAction(string $groupeAction): self
    {
        $this->groupeAction = $groupeAction;

        return $this;
    }

    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }

    public function setCompetence(?Competence $competence): self
    {
        $this->competence = $competence;

        return $this;
    }

    public function getstatus(): ?bool
    {
        return $this->status;
    }

    public function setstatus(bool $status): self
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
            $brief->addNiveau($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->removeElement($brief)) {
            $brief->removeNiveau($this);
        }

        return $this;
    }

    /**
     * @return Collection|LivrablePartiel[]
     */
    public function getLivrablePartiels(): Collection
    {
        return $this->livrablePartiels;
    }

    public function addLivrablePartiel(LivrablePartiel $livrablePartiel): self
    {
        if (!$this->livrablePartiels->contains($livrablePartiel)) {
            $this->livrablePartiels[] = $livrablePartiel;
            $livrablePartiel->addNiveau($this);
        }

        return $this;
    }

    public function removeLivrablePartiel(LivrablePartiel $livrablePartiel): self
    {
        if ($this->livrablePartiels->removeElement($livrablePartiel)) {
            $livrablePartiel->removeNiveau($this);
        }

        return $this;
    }
}
