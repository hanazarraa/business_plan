<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImpotsursocieteRepository")
 */
class Impotsursociete
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\businessplan")
     */
    private $businessplan;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\Column(type="json")
     */
    private $Versement = [];

    /**
     * @ORM\Column(type="json")
     */
    private $remboursement = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBusinessplan(): ?businessplan
    {
        return $this->businessplan;
    }

    public function setBusinessplan(?businessplan $businessplan): self
    {
        $this->businessplan = $businessplan;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getVersement(): ?array
    {
        return $this->Versement;
    }

    public function setVersement(array $Versement): self
    {
        $this->Versement = $Versement;

        return $this;
    }

    public function getRemboursement(): ?array
    {
        return $this->remboursement;
    }

    public function setRemboursement(array $remboursement): self
    {
        $this->remboursement = $remboursement;

        return $this;
    }
}
