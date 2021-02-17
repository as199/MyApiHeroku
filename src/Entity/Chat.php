<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ChatRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @ORM\Entity(repositoryClass=ChatRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *          "addChat":{ "method":"POST","path":"users/promo/{id}/apprenant/{id1}/chat","route_name":"AddingChat","denormalization_context"={"groups":"profil_sortis_post:write"}},
 *           "GetCHat":{ "method":"GET","path":"users/promo/{id}/apprenant/{id1}/chat/date","route_name":"gettingChat","normalization_context"={"groups":"profil_sortis_get:read"}},
 *     },
 *
 * )
 */
class Chat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"profil_sortis_get:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups ({"profil_sortis_post:write","profil_sortis_get:read"})
     */
    private $message;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups ({"profil_sortis_post:write"})
     */
    private $piecesJointes;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="chats")
     * @Groups ({"profil_sortis_post:write","profil_sortis_get:read"})
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="chats", cascade="persist")
     * @Groups ({"profil_sortis_post:write","profil_sortis_get:read"})
     */
    private $promo;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * Chat constructor.
     * @param $createdAt
     */
    public function __construct()
    {
        $this->createdAt = new DateTime("now");
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getPiecesJointes()
    {
        return $this->piecesJointes;
    }

    public function setPiecesJointes($piecesJointes): self
    {
        $this->piecesJointes = $piecesJointes;

        return $this;
    }

    public function getUsers(): ?Utilisateur
    {
        return $this->users;
    }

    public function setUsers(?Utilisateur $users): self
    {
        $this->users = $users;

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

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }


}
