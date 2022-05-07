<?php

namespace App\Entity;

use App\Repository\CategorieResRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=CategorieResRepository::class)
 * @UniqueEntity(
 * fields={"nom"},message="Le nom du categorie que vous avez tapé est déjà utilisé !"
 * )
 */
class CategorieRes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("categorie_res:read")
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
     * message="Le nom doit contenir seulement des lettres alphabétiques"
     * )   
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity=Reclamation::class, mappedBy="sujet")
     */
    private $reclamations;

    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

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
            $reclamation->setSujet($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): self
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getSujet() === $this) {
                $reclamation->setSujet(null);
            }
        }

        return $this;
    }
}
