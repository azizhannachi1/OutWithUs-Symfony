<?php

namespace App\Entity\Newsletters;

use App\Repository\Newsletters\CategoriesnRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriesnRepository::class)
 */
class Categoriesn
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Usersn::class, mappedBy="categoriesn")
     */
    private $usersn;

    /**
     * @ORM\OneToMany(targetEntity=Newsletters::class, mappedBy="Categoriesn", orphanRemoval=true)
     */
    private $newsletters;

    public function __construct()
    {
        $this->usersn = new ArrayCollection();
        $this->newsletters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Usersn>
     */
    public function getUsersn(): Collection
    {
        return $this->usersn;
    }

    public function addUser(Usersn $user): self
    {
        if (!$this->usersn->contains($user)) {
            $this->usersn[] = $user;
            $user->addCategory($this);
        }

        return $this;
    }

    public function removeUser(Usersn $user): self
    {
        if($this->usersn->removeElement($user)){
          $user->removeCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Newsletters>
     */
    public function getNewsletters(): Collection
    {
        return $this->newsletters;
    }

    public function addNewsletter(Newsletters $newsletter): self
    {
        if (!$this->newsletters->contains($newsletter)) {
            $this->newsletters[] = $newsletter;
            $newsletter->setCategoriesn($this);
        }

        return $this;
    }

    public function removeNewsletter(Newsletters $newsletter): self
    {
        if ($this->newsletters->removeElement($newsletter)) {
            // set the owning side to null (unless already changed)
            if ($newsletter->getCategoriesn() === $this) {
                $newsletter->setCategoriesn(null);
            }
        }

        return $this;
    }
}
