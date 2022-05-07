<?php

namespace App\Entity\Newsletters;

use App\Repository\Newsletters\UsersnRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity(repositoryClass=UsersnRepository::class)
 */
class Usersn
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="date")
     */
    private $created_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_rgpd = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $validation_token;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_valid = false;

    /**
     * @ORM\ManyToMany(targetEntity=Categoriesn::class, inversedBy="usersn")
     */
    private $categoriesn;

    public function __construct()
    {
        $this->created_at = new DateTime('now');
        $this->categoriesn = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

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

    public function getIsRgpd(): ?bool
    {
        return $this->is_rgpd;
    }

    public function setIsRgpd(bool $is_rgpd): self
    {
        $this->is_rgpd = $is_rgpd;

        return $this;
    }

    public function getValidationToken(): ?string
    {
        return $this->validation_token;
    }

    public function setValidationToken(string $validation_token): self
    {
        $this->validation_token = $validation_token;

        return $this;
    }

    public function getIsValid(): ?bool
    {
        return $this->is_valid;
    }

    public function setIsValid(bool $is_valid): self
    {
        $this->is_valid = $is_valid;

        return $this;
    }

    /**
     * @return Collection<int, Categoriesn>
     */
    public function getCategoriesn(): Collection
    {
        return $this->categoriesn;
    }

    public function addCategory(Categoriesn $category): self
    {
        if (!$this->categoriesn->contains($category)) {
            $this->categoriesn[] = $category;
            //$category->addUser($this);
        }

        return $this;
    }

    public function removeCategory(Categoriesn $category): self
    {
        $this->categoriesn->removeElement($category);

        return $this;
    }
}
