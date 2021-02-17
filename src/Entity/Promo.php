<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\PromoRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PromoRepository::class)
 * @ApiResource (
 *     routePrefix="/admin/",
 *     normalizationContext={"groups"={"promo:read"}},
 *     itemOperations={
 *          "GET",
 *          "GETRefFormGroup"={
 *               "path":"/promo/{id}",
 *                "method":"GET",
 *                 "normalizationContext"={"groups"={"RefFormGroup:read"}},
 *             },
 *             "formApprentReference"={
 *               "path":"/promo/{id}/principal",
 *                "method":"GET",
 *                 "normalizationContext"={"groups"={"formApprentReference:read"}},
 *             },"formReference"={
 *               "path":"/promo/{id}/referenciels",
 *                "method":"GET",
 *                 "normalizationContext"={"groups"={"formReference:read"}},
 *             },
 *   "referenceApprenantAttente":{
 *      "method":"get",
 *      "path":"/promo/{id}/apprenents/attente",
 *      "normalization_context"={"groups":"admin_promo_apprenant:read"},
 *      "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 * "promoGroupeApprenant":{
 *      "method":"get",
 *      "path":"/promo/{id1}/groupes/{id}/apprenants",
 *      "normalization_context"={"groups":"admin_promo_apprenant_groupes:read"},
 *      "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 *  "promoformateur":{
 *      "method":"get",
 *      "path":"/promo/{id}/formateurs",
 *      "normalization_context"={"groups":"admin_promo_referenciel_formateur:read"},
 *      "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 *  "modifierPromoRef":{
 *          "method":"PUT",
 *          "path":"/promo/{id}/referenciels",
 *          "normalization_context"={"groups":"modifierPromoRef:read"},
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT'))",
 *          "access_control_message"="Vous n'avez pas access à cette Ressource",
 *      },
 *  "modifierApprenant":{
 *          "method":"put",
 *          "path":"/promo/{id1}/referenciel",
 *          "normalization_context"={"groups":"apprenant_brief:read"},
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT'))",
 *          "access_control_message"="Vous n'avez pas access à cette Ressource",
 *      },
 *     "ModiefierSupprimerApprenant":{
 *          "method":"put",
 *          "path":"/promo/{id}/apprenants",
 *          "normalization_context"={"groups":"ModiefierSupprimerApprenant:read"},
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT'))",
 *          "access_control_message"="Vous n'avez pas access à cette Ressource",
 *      },
 *
 *     "ModiefierSupprimerFormateur":{
 *          "method":"put",
 *          "path":"/promo/{id1}/formateurs",
 *          "normalization_context"={"groups":"ModiefierSupprimerFormateur:read"},
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT'))",
 *          "access_control_message"="Vous n'avez pas access à cette Ressource",
 *      },
 *     "ModiefierSupprimerGroupe":{
 *          "method":"put",
 *          "path":"/promo/{id1}/groupes/{id}/status",
 *          "normalization_context"={"groups":"ModiefierSupprimerGroupe:read"},
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT'))",
 *          "access_control_message"="Vous n'avez pas access à cette Ressource",
 *      },
 *     "delPromo":{
 *      "method":"DELETE",
 *     "path":"/promos/{id}",
 *     "route_name"="deletePromo",
 *      "denormalizationContext"={"groups"={"promo:write"}},
 *          "access_control"="(is_granted('ROLE_ADMIN'))",
 *     "access_control_message"="Vous n'avez pas access à cette Ressource",
 *     },
 *  "PUTPromo":{
 *      "method":"PUT",
 *     "path":"/promo/{id}",
 *     "route_name"="editPromo",
 *      "denormalizationContext"={"groups"={"promo:write"}},
 *          "access_control"="(is_granted('ROLE_ADMIN'))",
 *     "access_control_message"="Vous n'avez pas access à cette Ressource",
 *     }},
 *     collectionOperations={
 *          "get_groupe_principale"={
 *              "method":"GET",
 *              "path":"/promos/principal",
 *              "normalizationContext"={"groups"={"promoprincipale:read"}},
 *          }, "get_apprenant_attente"={
 *              "method":"GET",
 *              "path":"/promo/apprenants/attente",
 *              "normalizationContext"={"groups"={"promoapprenant:read"}},
 *          },
 *          "formateurapprenant"={
 *               "method":"GET",
 *               "path":"/promo",
 *               "normalizationContext"={"groups"={"formateurapprenant:read"}},
 *           },
 *           "addpromo"={
 *               "method":"POST",
 *                "path":"/promo",
 *                 "route_name"="addPromo",
 *                "denormalizationContext"={"groups"={"promo:write"}},
 *            }
 *      }
 * )
 */
class Promo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"brief_groupe:read","profil_sortis_post:write",
     *     "profil_sortis_get:read","getReferencielApprenantCompetence:read",
     *     "promo:read","promoapprenant:read","RefFormGroup:read","promoprincipale:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"brief_groupe:read","getReferencielApprenantCompetence:read",
     *     "promo:read","promo:write","promoapprenant:read","RefFormGroup:read",
     *     "formApprentReference:read"})
     *@Assert\NotBlank(message="please enter your Promo name")
    */
    private $nomPromo;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups ({"promo:read","promo:write"})
     * @Assert\NotBlank(message="please enter your language")
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups ({"promo:read","promo:write"})
     * @Assert\NotBlank(message="please enter your title")
     */
    private $titre;

    /**
     * @ORM\Column(type="boolean")
     *  @Groups ({"promo:write","formateurapprenant:read","promo:read"})
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups ({"promo:read","promo:write"})
     * @Assert\NotBlank(message="please enter your description")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups ({"promo:read","promo:write"})
     * @Assert\NotBlank(message="please enter the lieu")
     */
    private $lieu;

    /**
     * @ORM\Column(type="date")
     *  @Groups ({"promo:read","promo:write"})
     * @Assert\NotBlank(message="please enter your date provisoire")
     */
    private $dateFinProvisoire;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups ({"promo:read","promo:write"})
     * @Assert\NotBlank(message="please enter fabrique name")
     */
    private $fabrique;

    /**
     * @ORM\Column(type="date")
     * @Groups ({"admin_groupe:read","promo:read","promo:write"})
     * @Assert\NotBlank(message="please enter end date")
     */
    private $dateFinReel;


    /**
     * @ORM\Column(type="blob", nullable=true)
     *  @Groups ({"admin_groupe:read","promo:read","promo:write"})
     */
    private $avatar;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, inversedBy="promos", cascade="persist")
     */
    private $groupes;

    /**
     * @ORM\OneToMany(targetEntity=CompetencesValides::class, mappedBy="promo" ,cascade="persist")
     */
    private $competencevalides;

    /**
     * @ORM\OneToMany(targetEntity=Chat::class, mappedBy="promo", cascade="persist")
     */
    private $chats;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="promo", cascade="persist")
     * @Groups ({"promo:read","promo:write","promoapprenant:read"})
     *
     */
    private $apprenants;

    /**
     * @ORM\ManyToMany(targetEntity=Referenciel::class, inversedBy="promos", cascade="persist")
     * @Groups ({"admin_promo_apprenant:read","promo:read"})
     */
    private $referenciels;

    /**
     * @ORM\OneToMany(targetEntity=BriefMaPromo::class, mappedBy="promo",cascade="persist")
     */
    private $briefMaPromo;



    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->competencevalides = new ArrayCollection();
        $this->chats = new ArrayCollection();
        $this->apprenants = new ArrayCollection();
        $this->referenciels = new ArrayCollection();
        $this->briefMaPromo = new ArrayCollection();
        $this->setStatus(false);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPromo(): ?string
    {
        return $this->nomPromo;
    }

    public function setNomPromo(string $nomPromo): self
    {
        $this->nomPromo = $nomPromo;

        return $this;
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

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDateFinProvisoire(): ?DateTimeInterface
    {
        return $this->dateFinProvisoire;
    }

    public function setDateFinProvisoire(DateTimeInterface $dateFinProvisoire): self
    {
        $this->dateFinProvisoire = $dateFinProvisoire;

        return $this;
    }

    public function getFabrique(): ?string
    {
        return $this->fabrique;
    }

    public function setFabrique(string $fabrique): self
    {
        $this->fabrique = $fabrique;

        return $this;
    }

    public function getDateFinReel(): ?DateTimeInterface
    {
        return $this->dateFinReel;
    }

    public function setDateFinReel(DateTimeInterface $dateFinReel): self
    {
        $this->dateFinReel = $dateFinReel;

        return $this;
    }

    public function getAvatar(): string
    {
        if ($this->avatar != null){
        return base64_encode(stream_get_contents($this->avatar));
        }else{
        return $this->avatar;
        }
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        $this->groupes->removeElement($groupe);

        return $this;
    }

    /**
     * @return Collection|CompetencesValides[]
     */
    public function getCompetencevalides(): Collection
    {
        return $this->competencevalides;
    }

    public function addCompetencevalide(CompetencesValides $competencevalide): self
    {
        if (!$this->competencevalides->contains($competencevalide)) {
            $this->competencevalides[] = $competencevalide;
            $competencevalide->setPromo($this);
        }

        return $this;
    }

    public function removeCompetencevalide(CompetencesValides $competencevalide): self
    {
        if ($this->competencevalides->removeElement($competencevalide)) {
            // set the owning side to null (unless already changed)
            if ($competencevalide->getPromo() === $this) {
                $competencevalide->setPromo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Chat[]
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats[] = $chat;
            $chat->setPromo($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->removeElement($chat)) {
            // set the owning side to null (unless already changed)
            if ($chat->getPromo() === $this) {
                $chat->setPromo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->setPromo($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getPromo() === $this) {
                $apprenant->setPromo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Referenciel[]
     */
    public function getReferenciels(): Collection
    {
        return $this->referenciels;
    }

    public function addReferenciel(Referenciel $referenciel): self
    {
        if (!$this->referenciels->contains($referenciel)) {
            $this->referenciels[] = $referenciel;
        }

        return $this;
    }

    public function removeReferenciel(Referenciel $referenciel): self
    {
        $this->referenciels->removeElement($referenciel);

        return $this;
    }

    /**
     * @return Collection|BriefMaPromo[]
     */
    public function getBriefMaPromo(): Collection
    {
        return $this->briefMaPromo;
    }

    public function addBriefMaPromo(BriefMaPromo $briefMaPromo): self
    {
        if (!$this->briefMaPromo->contains($briefMaPromo)) {
            $this->briefMaPromo[] = $briefMaPromo;
            $briefMaPromo->setPromo($this);
        }

        return $this;
    }

    public function removeBriefMaPromo(BriefMaPromo $briefMaPromo): self
    {
        if ($this->briefMaPromo->removeElement($briefMaPromo)) {
            // set the owning side to null (unless already changed)
            if ($briefMaPromo->getPromo() === $this) {
                $briefMaPromo->setPromo(null);
            }
        }

        return $this;
    }



}
