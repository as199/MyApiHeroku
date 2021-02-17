<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\BriefRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @ORM\Entity(repositoryClass=BriefRepository::class)
 * @ApiResource(
 *     routePrefix="/formateurs/",
 *     collectionOperations={
 *          "Get1":{"method":"GET","path":"/briefs","normalization_context"={"groups":"brief:read"}},
 *          "GetGroupe":{
 *     "method":"GET",
 *     "path":"/promo/{id}/groupe/{id1}/briefs",
 *     "route_name"="gettingBrief",
 *     "normalization_context"={"groups":"brief_groupe:read"}},
 *     "POST",
 *     }
 * )
 */
class Brief
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"brief:read","brief_groupe:read","brief_formateur_brouillon:read","brief_formateur_valide:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"brief:read","brief_groupe:read","brief_formateur_brouillon:read","brief_formateur_valide:read"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"brief:read","brief_groupe:read","brief_formateur_brouillon:read","brief_formateur_valide:read"})
     */
    private $nomBrief;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"brief:read","brief_groupe:read","brief_formateur_brouillon:read","brief_formateur_valide:read"})
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Tags::class, inversedBy="briefs")
     */
    private $tags;

    /**
     * @ORM\ManyToMany(targetEntity=Niveau::class, inversedBy="briefs")
     * @Groups ({"brief:read"})
     */
    private $niveaux;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="briefs")
     */
    private $formateurs;

    /**
     * @ORM\OneToMany(targetEntity=EtatBriefGroupe::class, mappedBy="brief")
     */
    private $etatBriefGroupes;

    /**
     * @ORM\ManyToMany(targetEntity=LivrableAttendu::class, mappedBy="briefs")
     * @Groups ({"brief:read"})
     */
    private $livrableAttendus;

    /**
     * @ORM\OneToMany(targetEntity=Ressource::class, mappedBy="briefs")
     */
    private $ressources;

    /**
     * @ORM\OneToMany(targetEntity=BriefMaPromo::class, mappedBy="briefs")
     *
     */
    private $briefMaPromos;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"brief:read","brief_groupe:read","brief_formateur_valide:read"})
     */
    private $critereEvaluation;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"brief:read","brief_groupe:read","brief_formateur_valide:read"})
     */
    private $modalitePedagogique;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"brief:read","brief_groupe:read","brief_formateur_valide:read"})
     */
    private $modalitesEvaluation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"brief:read","brief_groupe:read","brief_formateur_brouillon:read","brief_formateur_valide:read"})
     */
    private $statusBrief;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"brief:read"})
     */
    private $etat;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
        $this->etatBriefGroupes = new ArrayCollection();
        $this->livrableAttendus = new ArrayCollection();
        $this->ressources = new ArrayCollection();
        $this->briefMaPromos = new ArrayCollection();
        $this->createdAt = new DateTime("now");
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getNomBrief(): ?string
    {
        return $this->nomBrief;
    }

    public function setNomBrief(string $nomBrief): self
    {
        $this->nomBrief = $nomBrief;

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

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveaux->contains($niveau)) {
            $this->niveaux[] = $niveau;
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        $this->niveaux->removeElement($niveau);

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getFormateurs(): ?Formateur
    {
        return $this->formateurs;
    }

    public function setFormateurs(?Formateur $formateurs): self
    {
        $this->formateurs = $formateurs;

        return $this;
    }

    /**
     * @return Collection|EtatBriefGroupe[]
     */
    public function getEtatBriefGroupes(): Collection
    {
        return $this->etatBriefGroupes;
    }

    public function addEtatBriefGroupe(EtatBriefGroupe $etatBriefGroupe): self
    {
        if (!$this->etatBriefGroupes->contains($etatBriefGroupe)) {
            $this->etatBriefGroupes[] = $etatBriefGroupe;
            $etatBriefGroupe->setBrief($this);
        }

        return $this;
    }

    public function removeEtatBriefGroupe(EtatBriefGroupe $etatBriefGroupe): self
    {
        if ($this->etatBriefGroupes->removeElement($etatBriefGroupe)) {
            // set the owning side to null (unless already changed)
            if ($etatBriefGroupe->getBrief() === $this) {
                $etatBriefGroupe->setBrief(null);
            }
        }

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
            $livrableAttendu->addBrief($this);
        }

        return $this;
    }

    public function removeLivrableAttendu(LivrableAttendu $livrableAttendu): self
    {
        if ($this->livrableAttendus->removeElement($livrableAttendu)) {
            $livrableAttendu->removeBrief($this);
        }

        return $this;
    }

    /**
     * @return Collection|Ressource[]
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressource $ressource): self
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources[] = $ressource;
            $ressource->setBriefs($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): self
    {
        if ($this->ressources->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getBriefs() === $this) {
                $ressource->setBriefs(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BriefMaPromo[]
     */
    public function getBriefMaPromos(): Collection
    {
        return $this->briefMaPromos;
    }

    public function addBriefMaPromo(BriefMaPromo $briefMaPromo): self
    {
        if (!$this->briefMaPromos->contains($briefMaPromo)) {
            $this->briefMaPromos[] = $briefMaPromo;
            $briefMaPromo->setBriefs($this);
        }

        return $this;
    }

    public function removeBriefMaPromo(BriefMaPromo $briefMaPromo): self
    {
        if ($this->briefMaPromos->removeElement($briefMaPromo)) {
            // set the owning side to null (unless already changed)
            if ($briefMaPromo->getBriefs() === $this) {
                $briefMaPromo->setBriefs(null);
            }
        }

        return $this;
    }

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getModalitePedagogique(): ?string
    {
        return $this->modalitePedagogique;
    }

    public function setModalitePedagogique(?string $modalitePedagogique): self
    {
        $this->modalitePedagogique = $modalitePedagogique;

        return $this;
    }

    public function getModalitesEvaluation(): ?string
    {
        return $this->modalitesEvaluation;
    }

    public function setModalitesEvaluation(?string $modalitesEvaluation): self
    {
        $this->modalitesEvaluation = $modalitesEvaluation;

        return $this;
    }

    public function getStatusBrief(): ?string
    {
        return $this->statusBrief;
    }

    public function setStatusBrief(string $statusBrief): self
    {
        $this->statusBrief = $statusBrief;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
}
