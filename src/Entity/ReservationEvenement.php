<?php

namespace App\Entity;

use App\Repository\ReservationEvenementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationEvenementRepository::class)
 */
class ReservationEvenement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Evenement::class, inversedBy="reservationEvenements")
     */
    private $evenement;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservationEvenements")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;

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
