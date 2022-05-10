<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *  @Assert\NotBlank(message="Le Email ne peut pas etre vide")
     * @Assert\Email(
     *     message="l adresse  n est pas valide")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom ne peut pas etre vide")
     * @Assert\Length(
     * min = 3,
     * minMessage="Le nom  doit etre au minimum 3 lettres"
     * )
     * @Assert\Type(
     * type={"alpha"},
     * message="Le nom doit contenir seulement des lettres alphabétiques"
     * )   
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le prenom ne peut pas etre vide")
     * @Assert\Length(
     * min = 3,
     * minMessage="Le prenom  doit etre au minimum 3 lettres"
     * )
     * @Assert\Type(
     * type={"alpha"},
     * message="Le prenom doit contenir seulement des lettres alphabétiques"
     * )   
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activation_token;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom d'utilisateur ne peut pas etre vide")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reset_token;

    /**
     * @ORM\OneToMany(targetEntity=Reclamation::class, mappedBy="user")
     */
    private $reclamations;

    /**
     * @ORM\OneToMany(targetEntity=Publication::class, mappedBy="user")
     */
    private $publications;

     /**
     * @ORM\OneToMany(targetEntity=PublicationLike::class, mappedBy="user")
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=EvenementLike::class, mappedBy="user")
     */
    private $jaimes;

    /**
     * @ORM\OneToMany(targetEntity=ReservationEvenement::class, mappedBy="user")
     */
    private $reservationEvenements;

    /**
     * @ORM\OneToMany(targetEntity=Paiement::class, mappedBy="user")
     */
    private $paiements;
    
    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
        $this->publications = new ArrayCollection();  
        $this->likes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->jaimes = new ArrayCollection();
        $this->reservationEvenements = new ArrayCollection();
        $this->paiements = new ArrayCollection();   
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(?string $activation_token): self
    {
        $this->activation_token = $activation_token;

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

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): self
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations[] = $reclamation;
            $reclamation->setUser($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): self
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getUser() === $this) {
                $reclamation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Publication>
     */
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    public function addPublication(Publication $publication): self
    {
        if (!$this->publications->contains($publication)) {
            $this->publications[] = $publication;
            $publication->setUser($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): self
    {
        if ($this->publications->removeElement($publication)) {
            // set the owning side to null (unless already changed)
            if ($publication->getUser() === $this) {
                $publication->setUser(null);
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
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(PublicationLike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
            }
        }

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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
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
            $jaime->setUser($this);
        }

        return $this;
    }

    public function removeJaime(EvenementLike $jaime): self
    {
        if ($this->jaimes->removeElement($jaime)) {
            // set the owning side to null (unless already changed)
            if ($jaime->getUser() === $this) {
                $jaime->setUser(null);
            }
        }

        return $this;
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
            $reservationEvenement->setUser($this);
        }

        return $this;
    }

    public function removeReservationEvenement(ReservationEvenement $reservationEvenement): self
    {
        if ($this->reservationEvenements->removeElement($reservationEvenement)) {
            // set the owning side to null (unless already changed)
            if ($reservationEvenement->getUser() === $this) {
                $reservationEvenement->setUser(null);
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
            $paiement->setUser($this);
        }

        return $this;
    }

    public function removePaiement(Paiement $paiement): self
    {
        if ($this->paiements->removeElement($paiement)) {
            // set the owning side to null (unless already changed)
            if ($paiement->getUser() === $this) {
                $paiement->setUser(null);
            }
        }

        return $this;
    }

}
