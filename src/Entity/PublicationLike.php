<?php

namespace App\Entity;

use App\Repository\PublicationLikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PublicationLikeRepository::class)
 */
class PublicationLike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Publication::class, inversedBy="likes")
     */
    private $publication;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="likes")
     */
    private $user;

    /**
     * @var int|null
     *
     * @ORM\Column(name="utilisateur", type="integer", nullable=true)
     */
    private $utilisateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPublication(): ?Publication
    {
        return $this->publication;
    }

    public function setPublication(?Publication $publication): self
    {
        $this->publication = $publication;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

     public function getUtilisateur(): ?int
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?int $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
