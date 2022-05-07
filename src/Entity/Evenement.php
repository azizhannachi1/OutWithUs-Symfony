<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evenement
 *
 * @ORM\Table(name="evenement")
 * @ORM\Entity
 */
class Evenement
{
    /**
     * @var int
     *
     * @ORM\Column(name="IdEvent", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idevent;

    /**
     * @var string
     *
     * @ORM\Column(name="NomEvent", type="string", length=255, nullable=false)
     */
    private $nomevent;

    /**
     * @var string
     *
     * @ORM\Column(name="DescriptionEvent", type="string", length=255, nullable=false)
     */
    private $descriptionevent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateEvent", type="date", nullable=false)
     */
    private $dateevent;

    /**
     * @var float
     *
     * @ORM\Column(name="Prixevent", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixevent;

    public function getIdevent(): ?int
    {
        return $this->idevent;
    }

    public function getNomevent(): ?string
    {
        return $this->nomevent;
    }

    public function setNomevent(string $nomevent): self
    {
        $this->nomevent = $nomevent;

        return $this;
    }

    public function getDescriptionevent(): ?string
    {
        return $this->descriptionevent;
    }

    public function setDescriptionevent(string $descriptionevent): self
    {
        $this->descriptionevent = $descriptionevent;

        return $this;
    }

    public function getDateevent(): ?\DateTimeInterface
    {
        return $this->dateevent;
    }

    public function setDateevent(\DateTimeInterface $dateevent): self
    {
        $this->dateevent = $dateevent;

        return $this;
    }

    public function getPrixevent(): ?float
    {
        return $this->prixevent;
    }

    public function setPrixevent(float $prixevent): self
    {
        $this->prixevent = $prixevent;

        return $this;
    }


}
