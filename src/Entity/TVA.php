<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TVARepository")
 */
class TVA
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

 

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $remboursement = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\businessplan")
     */
    private $businessplan;

    public function getId(): ?int
    {
        return $this->id;
    }

    

    public function getRemboursement(): ?array
    {
        return $this->remboursement;
    }

    public function setRemboursement(?array $remboursement): self
    {
        $this->remboursement = $remboursement;

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

    public function getBusinessplan(): ?businessplan
    {
        return $this->businessplan;
    }

    public function setBusinessplan(?businessplan $businessplan): self
    {
        $this->businessplan = $businessplan;

        return $this;
    }
}
