<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollections;
use Doctrine\Common\Collections\Collections;

/**
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 */
class Comments
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="date")
     * @Groups("comments:read")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=Publication::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $publications;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $user_id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getPublications(): ?Publication
    {
        return $this->publications;
    }

    public function setPublications(?Publication $publications): self
    {
        $this->publications = $publications;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(?int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
