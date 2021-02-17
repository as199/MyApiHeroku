<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 * @ApiResource(attributes={"pagination_items_per_page"=5},
 *itemOperations={
 *"get_user_profil":{
 *      "method":"get",
 *      "path":"/admin/profils/{id}/users",
 *      "normalization_context"={"groups":"admin_profils_id:read"},
 *      "access_control"="(is_granted('ROLE_ADMIN') )",
 *       "access_control_message"="Vous n'avez pas access à cette Ressource",
 *     },
 *      "delete":{"access_control"="(is_granted('ROLE_ADMIN'))"},
 *     "get_on":{"method":"get","path":"/admin/profils/{id}","access_control"="(is_granted('ROLE_ADMIN') )",
 *               "access_control_message"="Vous n'avez pas access à cette Ressource",
*                 "normalization_context"={"groups":"admprofil:read"},
 *            },"put":{"path":"/admin/profils/{id}"},
 *     }
 *
 *     ,
 *
 *     collectionOperations={
 *
 *      "admin_profil":{
 *              "method": "GET",
 *               "path":"/admin/profils",
 *               "normalization_context"={"groups":"admin_profil:read"},
 *              "access_control"="(is_granted('ROLE_ADMIN') )",
 *               "access_control_message"="Vous n'avez pas access à cette Ressource",
 *         },
 *          "post",
 *
 * })
 *
 * @ApiFilter (SearchFilter::class, properties={"status": "exact"})
 * @UniqueEntity ("libelle")
 */
class Profil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"admin_profil:read","admin_profils_id:read","admin_profil_id:read","profil_id:read","admprofil:read","admin_user:read","adminv_user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin_profil:read","admin_profil_id:read","profil_id:read","profiladd:read","admprofil:read","admin_user:read","adminv_user:read"})
     *@Assert\NotBlank(message="please enter the libelle")
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Utilisateur::class, mappedBy="profil")
     * @Groups({"admin_profil_id:read","admin_profils_id:read"})
     *
     */
    private $users;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *  @Groups({"admin_profil:read","admin_profil_id:read","profil_id:read","profiladd:read","admprofil:read"})
     */
    private $status;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->status = false;
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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Utilisateur $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setProfil($this);
        }

        return $this;
    }

    public function removeUser(Utilisateur $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getProfil() === $this) {
                $user->setProfil(null);
            }
        }

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
}
