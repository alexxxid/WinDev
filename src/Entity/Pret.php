<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PretRepository")
 * @ApiResource()
 */
class Pret
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePret;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateRetourPrevue;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateRetourReel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Livre", inversedBy="adherant")
     * @ORM\JoinColumn(nullable=false)
     */
    private $livre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Adherent", inversedBy="prets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $adherent;

    public function getId(): ? int
    {
        return $this->id;
    }

    public function getDatePret(): ? \DateTimeInterface
    {
        return $this->datePret;
    }

    public function setDatePret(\DateTimeInterface $datePret): self
    {
        $this->datePret = $datePret;

        return $this;
    }

    public function getDateRetourPrevue(): ? \DateTimeInterface
    {
        return $this->dateRetourPrevue;
    }

    public function setDateRetourPrevue(\DateTimeInterface $dateRetourPrevue): self
    {
        $this->dateRetourPrevue = $dateRetourPrevue;

        return $this;
    }

    public function getDateRetourReel(): ? \DateTimeInterface
    {
        return $this->dateRetourReel;
    }

    public function setDateRetourReel(? \DateTimeInterface $dateRetourReel): self
    {
        $this->dateRetourReel = $dateRetourReel;

        return $this;
    }

    public function getLivre(): ? Livre
    {
        return $this->livre;
    }

    public function setLivre(? Livre $livre): self
    {
        $this->livre = $livre;

        return $this;
    }

    public function getAdherent(): ? Adherent
    {
        return $this->adherent;
    }

    public function setAdherent(? Adherent $adherent): self
    {
        $this->adherent = $adherent;

        return $this;
    }
}
