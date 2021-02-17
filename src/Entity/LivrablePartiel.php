<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LivrablePartielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LivrablePartielRepository::class)
 * @ApiResource(
 * collectionOperations={
 *    "get_livrablepartiel1"={
 *     "method"="GET",
 *     "normalizationContext"={"groups":{"livrablepartiel:read"}},
 *     "path"="/formateurs/promo/{id}/referentiel/{num}/competences",
 *     "access_control"="is_granted('ROLE_ADMIN')"
 *      },
 *     "get_livrablepartiel2"={
 *     "method"="GET",
 *     "normalizationContext"={"groups":{"livrablepartiel_stat:read",}},
 *     "path"="/formateurs/promo/{id}/referentiel/{num}/statistiques/competences",
 *     "route_name"="recuperer_stat_referentiel",
 *      "access_control"="is_granted('ROLE_FORMATEUR')"
 *      },
 *     "get_livrablepartiel3"={
 *     "method"="GET",
 *     "path"="/formateurs/livrablepartiels/{id}/commentaires",
 *     "controller":"App\Controller\LivrablePartielController::class",
 *     "route_name"="recuperer_les_commentaires",
 *     "access_control"="is_granted('ROLE_FORMATEUR')"
 *      },
 *     "post_livrablepartiel3"={
 *     "method"="POST",
 *     "normalizationContext"={"groups":{"livrablepartiel_comme:read"}},
 *     "path"="/formateurs/livrablepartiels/{id}/commentaires",
 *     "access_control"="is_granted('ROLE_FORMATEUR')"
 *      },
 *     "post_livrablepartiel4"={
 *     "method"="POST",
 *     "normalizationContext"={"groups":{"livrablepartiel_comme:read"}},
 *     "path"="/apprenants/livrablepartiels/{id}/commentaires",
 *     "access_control"="is_granted('ROLE_APPRENANT')"
 *      },
 *     "get_livrablepartiel4"={
 *     "method"="GET",
 *     "normalizationContext"={"groups":{"competenceV:read"}},
 *     "path"="/apprenant/{id}/promo/{id_a}/referentiel/{id_b}/competences",
 *     "access_control"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_APPRENANT'))"
 *      },
 *     "get_apprenant_brief":{
 *     "route_name"="apprenant_stat_briefs",
 *     "path"="/api/apprenants/{id}/promo/{idp}/referentiel/{idr}/statistiques/briefs",
 *     "methods"={"GET"},
 *     "normalizationContext"={"groups":{"competenceV:read"}},
 *     "controller":"App\Controller\LivrablePartielController::class",
 *     "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 *     },
 *
 *     },
 *     itemOperations={
 *      "get"={},
 *            "get_deux"={
 *          "method"="PUT",
 *          "path"="/api/apprenants/{id}/livrablepartiels/{id_d}",
 *          "route_name"="get_deux",
 *          "access_control"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_APPRENANT'))"
 *     },
 *     "get_deux_id"={
 *          "method"="PUT",
 *          "path"="/formateurs/promo/{id}/brief/{id_l}/livrablepartiels",
 *         "route_name"="get_deux_id",
 *         "access_control"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_APPRENANT'))"
 * },"get_livrablepartiel4"={
 *     "method"="GET",
 *     "normalizationContext"={"groups":{"livrablepartiel_GET:read"}},
 *     "path"="/apprenants/livrablepartiels/{id}/commentaires",
 *     "access_control"="is_granted('ROLE_ADMIN')"
 *      },
 *     }
 *
 * )
 * @UniqueEntity ("libelle")
 */
class LivrablePartiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"livrPart:read","livrablepartiel:read","livrablepartiel_GET:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="please enter the libelle")
     * @Groups ({"livrPart:read","livrablepartiel:read","livrablepartiel_GET:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="please enter the delai")
     * @Groups ({"livrPart:read","livrablepartiel:read"})
     */
    private $delai;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="please enter the type")
     * @Groups ({"livrPart:read"})
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="please enter number finished")
     * @Groups ({"livrPart:read"})
     */
    private $nombreRendu;

    /**
     * @ORM\ManyToMany(targetEntity=Niveau::class, inversedBy="livrablePartiels")
     */
    private $niveau;

    /**
     * @ORM\OneToMany(targetEntity=ApprenantLivrablePartiel::class, mappedBy="livrablePartiels")
     */
    private $apprenantLivrablePartiels;

    /**
     * @ORM\ManyToOne(targetEntity=BriefMaPromo::class, inversedBy="livrablePartiel")
     */
    private $briefMaPromo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="please enter your description")
     * @Groups ({"livrPart:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="please enter count corrige")
     * @Groups ({"livrPart:read"})
     */
    private $nombreCorrige;

    /**
     * @ORM\Column(type="boolean")
     * @Groups ({"livrPart:read"})
     */
    private $status;

    public function __construct()
    {
        $this->niveau = new ArrayCollection();
        $this->apprenantLivrablePartiels = new ArrayCollection();
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

    public function getDelai(): ?string
    {
        return $this->delai;
    }

    public function setDelai(string $delai): self
    {
        $this->delai = $delai;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNombreRendu(): ?int
    {
        return $this->nombreRendu;
    }

    public function setNombreRendu(int $nombreRendu): self
    {
        $this->nombreRendu = $nombreRendu;

        return $this;
    }

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveau(): Collection
    {
        return $this->niveau;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveau->contains($niveau)) {
            $this->niveau[] = $niveau;
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        $this->niveau->removeElement($niveau);

        return $this;
    }

    /**
     * @return Collection|ApprenantLivrablePartiel[]
     */
    public function getApprenantLivrablePartiels(): Collection
    {
        return $this->apprenantLivrablePartiels;
    }

    public function addApprenantLivrablePartiel(ApprenantLivrablePartiel $apprenantLivrablePartiel): self
    {
        if (!$this->apprenantLivrablePartiels->contains($apprenantLivrablePartiel)) {
            $this->apprenantLivrablePartiels[] = $apprenantLivrablePartiel;
            $apprenantLivrablePartiel->setLivrablePartiels($this);
        }

        return $this;
    }

    public function removeApprenantLivrablePartiel(ApprenantLivrablePartiel $apprenantLivrablePartiel): self
    {
        if ($this->apprenantLivrablePartiels->removeElement($apprenantLivrablePartiel)) {
            // set the owning side to null (unless already changed)
            if ($apprenantLivrablePartiel->getLivrablePartiels() === $this) {
                $apprenantLivrablePartiel->setLivrablePartiels(null);
            }
        }

        return $this;
    }

    public function getBriefMaPromo(): ?BriefMaPromo
    {
        return $this->briefMaPromo;
    }

    public function setBriefMaPromo(?BriefMaPromo $briefMaPromo): self
    {
        $this->briefMaPromo = $briefMaPromo;

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

    public function getNombreCorrige(): ?int
    {
        return $this->nombreCorrige;
    }

    public function setNombreCorrige(int $nombreCorrige): self
    {
        $this->nombreCorrige = $nombreCorrige;

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
