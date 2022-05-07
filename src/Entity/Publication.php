<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PublicationRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollections;
use Doctrine\Common\Collections\Collections;
use Sension\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Users;


/**
 * Publication
 *
 * @ORM\Table(name="publication", indexes={@ORM\Index(name="userId_fk", columns={"userId"})})
 * @ORM\Entity
 */
class Publication
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Titre", type="string", length=255, nullable=false)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="Text", type="string", length=255, nullable=false)
     */
    private $text;

    /**
     * @var int|null
     *
     * @ORM\Column(name="userId", type="integer", nullable=true)
     */
    private $userid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var string|null
     *
     * @ORM\Column(name="userEmail", type="string", length=255, nullable=true)
     */
    private $useremail;

    /**
     *   
     * @ORM\Column(type="date")
     * @Groups("publication:read")
     * 
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="publications", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=PublicationLike::class, mappedBy="publication")
     */
    private $likes;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getUserid(): ?int
    {
        return $this->userid;
    }

    public function setUserid(?int $userid): self
    {
        $this->userid = $userid;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getUseremail(): ?string
    {
        return $this->useremail;
    }

    public function setUseremail(?string $useremail): self
    {
        $this->useremail = $useremail;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPublications($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPublications() === $this) {
                $comment->setPublications(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PublicationLike>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(PublicationLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setPublication($this);
        }

        return $this;
    }

    public function removeLike(PublicationLike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getPublication() === $this) {
                $like->setPublication(null);
            }
        }

        return $this;
    }



 /**
     * @param $userid
     * @return bool
     */
    public function isLikeByUserId($userid):bool{
        foreach ($this->likes as $Like){
            if ($Like->getUtilisateur() === $userid)return true;
        }
        return false;
    }


}
