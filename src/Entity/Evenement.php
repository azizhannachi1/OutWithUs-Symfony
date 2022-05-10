<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EvenementRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
{
      /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Nom Evenement est requis")
     */
    private $NomEvent;

    /**
     * @ORM\Column(type="string", length=100) 
     * @Assert\NotBlank(message="Description est requise")
     */
    private $DescriptionEvent;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="Date est requise")
     */
    private $DateEvent;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Prix est requis")
     */
    private $PrixEvent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="evenement")
     */
    private $likes;

     /**
     * @ORM\OneToMany(targetEntity=Dislike::class, mappedBy="evenement")
     */
    private $dislikes;

    /**
     * @ORM\OneToMany(targetEntity=EvenementLike::class, mappedBy="evenement")
     */
    private $jaimes;

    /**
     * @ORM\OneToMany(targetEntity=ReservationEvenement::class, mappedBy="evenement")
     */
    private $reservationEvenements;

    /**
     * @ORM\OneToMany(targetEntity=Paiement::class, mappedBy="evenement")
     */
    private $paiements;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->dislikes = new ArrayCollection();
        $this->jaimes = new ArrayCollection();
        $this->reservationEvenements = new ArrayCollection();
        $this->paiements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEvent(): ?string
    {
        return $this->NomEvent;
    }

    public function setNomEvent(string $NomEvent): self
    {
        $this->NomEvent = $NomEvent;

        return $this;
    }

    public function getDescriptionEvent(): ?string
    {
        return $this->DescriptionEvent;
    }

    public function setDescriptionEvent(string $DescriptionEvent): self
    {
        $this->DescriptionEvent = $DescriptionEvent;

        return $this;
    }

    public function getDateEvent(): ?\DateTimeInterface
    {
        return $this->DateEvent;
    }

    public function setDateEvent(\DateTimeInterface $DateEvent): self
    {
        $this->DateEvent = $DateEvent;

        return $this;
    }

    public function getPrixEvent(): ?float
    {
        return $this->PrixEvent;
    }

    public function __toString()
{
    return (string) $this->getPrixEvent();
}

    public function setPrixEvent(float $PrixEvent): self
    {
        $this->PrixEvent = $PrixEvent;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

     /**
     * @return Collection|Like[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setEvenement($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getEvenement() === $this) {
                $like->setEvenement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Dislike[]
     */
    public function getDislikes(): Collection
    {
        return $this->dislikes;
    }

    public function addDislike(Dislike $dislike): self
    {
        if (!$this->dislikes->contains($dislike)) {
            $this->dislikes[] = $dislike;
            $dislike->setEvenement($this);
        }

        return $this;
    }

    public function removeDislike(Dislike $dislike): self
    {
        if ($this->dislikes->removeElement($dislike)) {
            // set the owning side to null (unless already changed)
            if ($dislike->getEvenement() === $this) {
                $dislike->setEvenement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EvenementLike>
     */
    public function getJaimes(): Collection
    {
        return $this->jaimes;
    }

    public function addJaime(EvenementLike $jaime): self
    {
        if (!$this->jaimes->contains($jaime)) {
            $this->jaimes[] = $jaime;
            $jaime->setEvenement($this);
        }

        return $this;
    }

    public function removeJaime(EvenementLike $jaime): self
    {
        if ($this->jaimes->removeElement($jaime)) {
            // set the owning side to null (unless already changed)
            if ($jaime->getEvenement() === $this) {
                $jaime->setEvenement(null);
            }
        }

        return $this;
    }

        /**
     * @param \App\Entity\User $user
     * @return bool
     */
    public function isLikeByUser(User $user):bool{
        foreach ($this->jaimes as $Jaime){
            if ($Jaime->getUser() === $user)return true;
        }
        return false;
    }

    public function nblike(){
        return $this->jaimes->count();
    }

    /**
     * @return Collection<int, ReservationEvenement>
     */
    public function getReservationEvenements(): Collection
    {
        return $this->reservationEvenements;
    }

    public function addReservationEvenement(ReservationEvenement $reservationEvenement): self
    {
        if (!$this->reservationEvenements->contains($reservationEvenement)) {
            $this->reservationEvenements[] = $reservationEvenement;
            $reservationEvenement->setEvenement($this);
        }

        return $this;
    }

    public function removeReservationEvenement(ReservationEvenement $reservationEvenement): self
    {
        if ($this->reservationEvenements->removeElement($reservationEvenement)) {
            // set the owning side to null (unless already changed)
            if ($reservationEvenement->getEvenement() === $this) {
                $reservationEvenement->setEvenement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Paiement>
     */
    public function getPaiements(): Collection
    {
        return $this->paiements;
    }

    public function addPaiement(Paiement $paiement): self
    {
        if (!$this->paiements->contains($paiement)) {
            $this->paiements[] = $paiement;
            $paiement->setEvenement($this);
        }

        return $this;
    }

    public function removePaiement(Paiement $paiement): self
    {
        if ($this->paiements->removeElement($paiement)) {
            // set the owning side to null (unless already changed)
            if ($paiement->getEvenement() === $this) {
                $paiement->setEvenement(null);
            }
        }

        return $this;
    }
    
}
