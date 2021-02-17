<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Controller\ProfilController;
use App\Controller\ProfilSortiController;
use App\Repository\ProfilSortiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProfilSortiRepository::class)
 * @ApiResource (
 *     routePrefix="/admin/",
 *     collectionOperations={
  *      "GET":{ "method":"GET","path":"/profil_sortis","normalization_context"={"groups":"profil_sortis:read"}},
 *        "POST":{ "method":"POST","path":"/profil_sortis","denormalization_context"={"groups":"profil_sortis_post:write"}},
 *           "afficherAppProfilSorti":{ "method":"GET","path":"/promo/{id}/profilsorties/{id2}/apprenants","normalization_context"={"groups":"profil_sortis_apprenant:read"}},
 *     },
 *     itemOperations={
 *            "GET":{ "method":"GET","path":"/profil_sortis/{id}","normalization_context"={"groups":"profil_sortis_id:read"}},
 *              "PUT":{ "method":"PUT","path":"/profil_sortis/{id}","denormalization_context"={"groups":"profil_sortis_post:write"}},
 *              "Delete":{ "method":"DELETE","path":"/profil_sortis/{id}","denormalization_context"={"groups":"profil_sortisd_post:write"}},
 *
 *     }
 * )
 */
class ProfilSorti
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"profil_sortis:read","profil_sortisd_post:write"})
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"profil_sortis_post:write","profil_sortis:read","profil_sortis_id:read","profil_sortisd_post:write"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     * @Groups ({"profil_sortis_post:write","profil_sortis:read","profil_sortis_id:read","profil_sortisd_post:write"})
     */
    private $status = false;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="profilSorti" ,cascade="persist")
     * @Groups ({"profil_sortis_post:write","profil_sortis_id:read","profil_sortis_apprenant:read"})
     */
    private $apprenants;

    public function __construct(  )
    {
        $this->apprenants = new ArrayCollection();
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
            $apprenant->setProfilSorti($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getProfilSorti() === $this) {
                $apprenant->setProfilSorti(null);
            }
        }

        return $this;
    }
}
