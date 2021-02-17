<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ApprenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApprenantRepository", repositoryClass=ApprenantRepository::class)
 * @ApiResource(attributes={"pagination_enabled"=true,"pagination_items_per_page"=1,},
 *     itemOperations={
 *     "get_apprenant_id":{
 *           "method":"get",
 *          "path":"/apprenants/{id}",
 *              "access_control"="(is_granted('ROLE_ADMIN')  or is_granted('ROLE_FORMATEUR') or object==user or is_granted('ROLE_APPRENANT'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          },"put_apprenant_id":{
 *           "method":"put",
 *          "path":"/apprenants/{id}",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_APPRENANT'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          }
*      },
 *     collectionOperations={
 *     "get":{
 *              "path":"/apprenants",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_APPRENANT'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "deserialize" = false
 *          },
*     "getApprenantAttente":{
 *              "path":"/apprenants/attente",
 *               "method":"GET",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_APPRENANT'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
  *           "normalization_context"={"groups":"apprenantAttente:read"},
 *              
 *          },
 *     "addApprenant":{
 *               "method":"post",
 *              "path":"/apprenants",
 *               "deserialize" = false
 *              }
 *
 *      }
 * )
 * @ApiFilter(SearchFilter::class, properties={"attente": "exact"})
 */
class Apprenant extends Utilisateur
{
    /**
     * @ORM\ManyToOne(targetEntity=ProfilSorti::class, inversedBy="apprenants")
     * @Groups ({"admin_profilsorties:read"})
     *
     */
    private $profilSorti;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="apprenants")
     *  @Groups ({"admin_profilsorties:read"})
     */
    private $groupes;

    /**
     * @ORM\OneToMany(targetEntity=CompetencesValides::class, mappedBy="apprenants")
     */
    private $competencesValides;

    /**
     * @ORM\OneToMany(targetEntity=BriefApprenant::class, mappedBy="apprenants")
     */
    private $briefApprenants;

    /**
     * @ORM\OneToMany(targetEntity=LivrableAttenduApprenant::class, mappedBy="apprenants")
     */
    private $livrableAttenduApprenants;

    /**
     * @ORM\OneToMany(targetEntity=ApprenantLivrablePartiel::class, mappedBy="apprenants")
     */
    private $apprenantLivrablePartiels;

    /**
     * @ORM\Column(type="boolean")
     * @Groups ({"promo:read","promo:write","apprenantAttente:read"})
     */
    private $attente;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="apprenants",cascade="persist")
     */
    private $promo;



    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->competencesValides = new ArrayCollection();
        $this->briefApprenants = new ArrayCollection();
        $this->livrableAttenduApprenants = new ArrayCollection();
        $this->apprenantLivrablePartiels = new ArrayCollection();
        $this->attente = true;
    }

    public function getProfilSorti(): ?ProfilSorti
    {
        return $this->profilSorti;
    }

    public function setProfilSorti(?ProfilSorti $profilSorti): self
    {
        $this->profilSorti = $profilSorti;

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
            $groupe->addApprenant($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeApprenant($this);
        }

        return $this;
    }

    /**
     * @return Collection|CompetencesValides[]
     */
    public function getCompetencesValides(): Collection
    {
        return $this->competencesValides;
    }

    public function addCompetencesValide(CompetencesValides $competencesValide): self
    {
        if (!$this->competencesValides->contains($competencesValide)) {
            $this->competencesValides[] = $competencesValide;
            $competencesValide->setApprenants($this);
        }

        return $this;
    }

    public function removeCompetencesValide(CompetencesValides $competencesValide): self
    {
        if ($this->competencesValides->removeElement($competencesValide)) {
            // set the owning side to null (unless already changed)
            if ($competencesValide->getApprenants() === $this) {
                $competencesValide->setApprenants(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BriefApprenant[]
     */
    public function getBriefApprenants(): Collection
    {
        return $this->briefApprenants;
    }

    public function addBriefApprenant(BriefApprenant $briefApprenant): self
    {
        if (!$this->briefApprenants->contains($briefApprenant)) {
            $this->briefApprenants[] = $briefApprenant;
            $briefApprenant->setApprenants($this);
        }

        return $this;
    }

    public function removeBriefApprenant(BriefApprenant $briefApprenant): self
    {
        if ($this->briefApprenants->removeElement($briefApprenant)) {
            // set the owning side to null (unless already changed)
            if ($briefApprenant->getApprenants() === $this) {
                $briefApprenant->setApprenants(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LivrableAttenduApprenant[]
     */
    public function getLivrableAttenduApprenants(): Collection
    {
        return $this->livrableAttenduApprenants;
    }

    public function addLivrableAttenduApprenant(LivrableAttenduApprenant $livrableAttenduApprenant): self
    {
        if (!$this->livrableAttenduApprenants->contains($livrableAttenduApprenant)) {
            $this->livrableAttenduApprenants[] = $livrableAttenduApprenant;
            $livrableAttenduApprenant->setApprenants($this);
        }

        return $this;
    }

    public function removeLivrableAttenduApprenant(LivrableAttenduApprenant $livrableAttenduApprenant): self
    {
        if ($this->livrableAttenduApprenants->removeElement($livrableAttenduApprenant)) {
            // set the owning side to null (unless already changed)
            if ($livrableAttenduApprenant->getApprenants() === $this) {
                $livrableAttenduApprenant->setApprenants(null);
            }
        }

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
            $apprenantLivrablePartiel->setApprenants($this);
        }

        return $this;
    }

    public function removeApprenantLivrablePartiel(ApprenantLivrablePartiel $apprenantLivrablePartiel): self
    {
        if ($this->apprenantLivrablePartiels->removeElement($apprenantLivrablePartiel)) {
            // set the owning side to null (unless already changed)
            if ($apprenantLivrablePartiel->getApprenants() === $this) {
                $apprenantLivrablePartiel->setApprenants(null);
            }
        }

        return $this;
    }

    public function getAttente(): ?bool
    {
        return $this->attente;
    }

    public function setAttente(bool $attente): self
    {
        $this->attente = $attente;

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
