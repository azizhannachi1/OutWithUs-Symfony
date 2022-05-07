<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReclamationRepository::class)
 */
class Reclamation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom ne peut pas etre vide")
     * @Assert\Length(
     * min = 3,
     * minMessage="Le nom  doit etre au minimum 3 lettres"
     * )
     * @Assert\Type(
     * type={"alpha"},
     * message="Votre nom doit contenir seulement des lettres alphabétiques"
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank(message="Le prenom ne peut pas etre vide")
     * @Assert\Length(
     * min = 3,
     * minMessage="Le prenom  doit etre au minimum 3 lettres"
     * )
     * @Assert\Type(
     * type={"alpha"},
     * message="Votre prenom doit contenir seulement des lettres alphabétiques"
     * )
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le Email ne peut pas etre vide")
     * @Assert\Email(
     *     message="l adresse  n est pas valide")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank(message="Le message ne peut pas etre vide")
     * @Assert\Length(
     * min = 3,
     * minMessage="Le message doit etre au minimum 3 lettres"
     * )
     */
    private $message;

    /**
     * @ORM\Column(type="date")
     * @Groups("reclamation:read")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=CategorieRes::class, inversedBy="reclamations")
     */
    private $sujet;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;

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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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

    public function getSujet(): ?CategorieRes
    {
        return $this->sujet;
    }

    public function setSujet(?CategorieRes $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }
}
