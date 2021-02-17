<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\GroupeCompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GroupeCompetenceRepository::class)
 * @ApiResource(
 * itemOperations={
 * "get_groupe_competence_id":{
 *   "method": "GET",
 *     "normalization_context"={"groups":"gcfr:read"},
 *   "path": "/admin/grpecompetences/{id}",
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 * },
 * "updateCompetenceId":{
 *   "method": "PUT",
 *     "route_name"="putGroupcompetence",
 *   "denormalizationContext"={"groups":"gprecompetence:write"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 * },
 * "deleteGrpCompetenceId":{
 *    "method": "DELETE",
 *    "denormalizationContext"={"groups"={"gcudelete:write"}},
 *    "route_name"="deleteGroupcompetence",
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 * },
 * "get_competence_id":{
 *   "method": "GET",
 *   "path": "/admin/grpecompetences/{id}/competences",
 *   "normalization_context"={"groups":"gcf:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 * }
 * },
 *  collectionOperations={
 *   "get_groupe_competences": {
 *   "method": "GET",
 *   "path": "/admin/grpecompetences",
 *   "normalization_context"={"groups":"gprecompetence:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 *     "get_all_groupe_competences": {
 *   "method": "GET",
 *   "path": "/grpecompetences",
 *   "normalization_context"={"groups":"gprecompetence:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 * "get_lister_competence_dans_groupes": {
 *    "method": "GET",
 *    "path": "/admin/grpecompetences/competences",
 *    "normalization_context"={"groups":"gcu:read"},
 *    "access_control"="(is_granted('ROLE_ADMIN'))",
 *    "access_control_message"="Vous n'avez pas access à cette Ressource",
 *   },
 * "AddGroupeCompetences": {
 *     "route_name"="groupcompadd",
 *    "method": "POST",
 *    "path": "/admin/grpecompetences",
 *     "denormalization_context"={"groups":"gcuadd:read"},
 *    "access_control"="(is_granted('ROLE_ADMIN'))",
 *    "access_control_message"="Vous n'avez pas access à cette Ressource",
 *
 *   }
 * }
 * )
 * @UniqueEntity ("libelle")
 */
class GroupeCompetence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *  @Groups ({"formReference:read","gcudelete:write","renciel:read","Deletegprecompetence:read","gcfr:read","competence:read","gcfr:read","competences:read","competence:write","competence:read","gprecompetence:read","gc:read","gprecompetences:read","referenciel:read","referenciel:write","referencielgroupe:read","referencielgroupeComp:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups ({"formReference:read","gprecompetence:write","renciel:read","gcfr:read","gcuadd:write","competences:read","competence:read","competence:read","gprecompetence:read","gc:read","gprecompetences:read","referenciel:read","referenciel:write","referencielgroupe:read","referencielgroupeComp:read"})
     */
    private $libelle;



    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups ({"formReference:read","gprecompetence:write","renciel:read","gcfr:read","gcuadd:write","competence:read","gprecompetence:read","referenciel:read","referenciel:write","referencielgroupe:read","referencielgroupeComp:read"})
     *
     */
    private $descriptif;

    /**
     * @ORM\Column(type="boolean")
     *  @Groups ({"competence:read","gprecompetence:write","Deletegprecompetence:read","gprecompetence:read","gcudelete:write","referenciel:write","referencielgroupe:read"})
     */
    private $status;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="groupeCompetences")
     * @Groups ({"formReference:read","gprecompetence:write","gcfr:read","gcuadd:write","gprecompetence:read","gcu:read","gcf:read","referencielgroupe:read"})
     * @ApiSubresource()
     */
    private $competences;

    /**
     * @ORM\ManyToMany(targetEntity=Referenciel::class, mappedBy="groupeCompetence")
     * @Groups ({"gcuadd:write"})
     */
    private $referenciels;

    public function __construct()
    {

        $this->status = false;
        $this->competences = new ArrayCollection();
        $this->referenciels = new ArrayCollection();
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


    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

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

    /**
     * @return Collection|Competence[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        $this->competences->removeElement($competence);

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
            $referenciel->addGroupeCompetence($this);
        }

        return $this;
    }

    public function removeReferenciel(Referenciel $referenciel): self
    {
        if ($this->referenciels->removeElement($referenciel)) {
            $referenciel->removeGroupeCompetence($this);
        }

        return $this;
    }
}
