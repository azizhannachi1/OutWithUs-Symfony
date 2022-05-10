<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Paiement
 *
 * @ORM\Table(name="paiement")
 * @ORM\Entity
 */
class Paiement
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
     * @ORM\Column(name="carte", type="string", length=255, nullable=false)
     */
    private $carte;

    /**
     * @var string
     *
     * @ORM\Column(name="year", type="string", length=255, nullable=false)
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="month", type="string", length=255, nullable=false)
     */
    private $month;

    /**
     * @var string
     *
     * @ORM\Column(name="cvc", type="string", length=255, nullable=false)
     */
    private $cvc;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="string", length=255, nullable=false)
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=false, options={"default"="Pending"})
     */
    private $status = 'Pending';

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=Evenement::class, inversedBy="paiements")
     */
    private $evenement;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="paiements")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarte(): ?string
    {
        return $this->carte;
    }

    public function setCarte(string $carte): self
    {
        $this->carte = $carte;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(string $month): self
    {
        $this->month = $month;

        return $this;
    }

    public function getCvc(): ?string
    {
        return $this->cvc;
    }

    public function setCvc(string $cvc): self
    {
        $this->cvc = $cvc;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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
