<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\UsersRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * * @UniqueEntity(
 * fields={"email"},message="L'émail que vous avez tapé est déjà utilisé !"
 * )
 */
class Users implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @Assert\Email(
     *     message="l adresse  n est pas valide")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     * @Assert\Length(
     * min = 2,
     * max = 20,
     * minMessage = "Votre nom doit contenir au moins 2 caract`eres",
     * maxMessage = "Votre nom doit contenir au plus 20 caract`eres",
     * allowEmptyString = false
     * )
     */
    private $username;

    /**
     * @var string The hashed password
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     * @Assert\Length(
     * min = 8,
     * minMessage = "Votre mot de passe doit comporter au minimum 8 caractères")
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255, nullable=false, options={"default"="user"})
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=PublicationLike::class, mappedBy="user")
     */
    private $likes;

    /**
     * @Assert\EqualTo(propertyPath = "password",
     * message="Vous n'avez pas saisi le même mot de passe !" )
     */
    private $confirm_password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activation_token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    { $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
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

    
    public function eraseCredentials() {}
    public function getSalt() {}

    public function getConfirmPassword()
    {
        return $this->confirm_password;
    }
    public function setConfirmPassword($confirm_password)
    {
        $this->confirm_password = $confirm_password;
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


}
