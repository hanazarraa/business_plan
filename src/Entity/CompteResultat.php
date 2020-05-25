<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompteResultatRepository")
 */
class CompteResultat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json")
     */
    private $RD = [];

    /**
     * @ORM\Column(type="json")
     */
    private $tauximpot = [];

    /**
     * @ORM\Column(type="json")
     */
    private $creditimpot = [];

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Businessplan", cascade={"persist", "remove"})
     */
    private $businessplan;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRD(): ?array
    {
        return $this->RD;
    }

    public function setRD(array $RD): self
    {
        $this->RD = $RD;

        return $this;
    }

    public function getTauximpot(): ?array
    {
        return $this->tauximpot;
    }

    public function setTauximpot(array $tauximpot): self
    {
        $this->tauximpot = $tauximpot;

        return $this;
    }

    public function getCreditimpot(): ?array
    {
        return $this->creditimpot;
    }

    public function setCreditimpot(array $creditimpot): self
    {
        $this->creditimpot = $creditimpot;

        return $this;
    }

    public function getBusinessplan(): ?Businessplan
    {
        return $this->businessplan;
    }

    public function setBusinessplan(?Businessplan $businessplan): self
    {
        $this->businessplan = $businessplan;

        return $this;
    }
}
