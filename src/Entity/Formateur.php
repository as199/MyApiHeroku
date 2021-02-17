<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FormateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 * @ApiResource (itemOperations={
 *                  "get_formateur_id":{
*                       "method":"get",
 *                      "path":"/formateurs/{id}",
 *                       "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *                       "access_control_message"="Vous n'avez pas access à cette Ressource",
 *
 *                    },
 *
 *                  "put_formateur_id":{
 *                       "method":"put",
 *                      "path":"/formateurs/{id}",
 *                       "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *                       "access_control_message"="Vous n'avez pas access à cette Ressource",
 *
 *                    }
*               },
 *     collectionOperations={
 *          "get":{
 *              "path":"/formateurs",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')or is_granted('ROLE_CM') )",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *               },
 *              "get_formateur_id_brief":{
 *                       "method":"get",
 *                      "path":"/formateurs/{id}/briefs/brouillon",
 *                        "normalization_context"={"groups":"brief_formateur_brouillon:read"},
 *                       "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *                       "access_control_message"="Vous n'avez pas access à cette Ressource",
 *
 *                    },
 *                  "get_formateur_id_brief_valide":{
 *                       "method":"get",
 *                      "path":"/formateurs/{id}/briefs/valide",
 *                        "normalization_context"={"groups":"brief_formateur_valide:read"},
 *                       "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *                       "access_control_message"="Vous n'avez pas access à cette Ressource",
 *
 *                    },
 *                      "get_formateur_id_brief_promo":{
 *                       "method":"get",
 *                      "path":"/formateurs/promo/{id}/briefs/{id1}/brief",
 *                        "normalization_context"={"groups":"brief_formateur_valide:read"},
 *                       "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *                       "access_control_message"="Vous n'avez pas access à cette Ressource",
 *
 *                    },
 *      }
 *     )
 */
class Formateur extends Utilisateur
{
    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="formateurs")
     */
    private $groupes;

    /**
     * @ORM\OneToMany(targetEntity=Brief::class, mappedBy="formateurs")
     * @Groups ({"brief_formateur_brouillon:read","brief_formateur_valide:read"})
     */
    private $briefs;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="formateurs")
     */
    private $commentaires;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->briefs = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
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
            $groupe->addFormateur($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeFormateur($this);
        }

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
            $brief->setFormateurs($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->removeElement($brief)) {
            // set the owning side to null (unless already changed)
            if ($brief->getFormateurs() === $this) {
                $brief->setFormateurs(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setFormateurs($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getFormateurs() === $this) {
                $commentaire->setFormateurs(null);
            }
        }

        return $this;
    }
}
