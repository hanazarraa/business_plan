<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SalesdetailledRepository")
 */
class Salesdetailled
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\Column(type="json")
     */
    private $detailled = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sales", inversedBy="detail")
     */
    private $sales;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDetailled(): ?array
    {
        return $this->detailled;
    }

    public function setDetailled(array $detailled): self
    {
        $this->detailled = $detailled;

        return $this;
    }

    public function getSales(): ?Sales
    {
        return $this->sales;
    }

    public function setSales(?Sales $sales): self
    {
        $this->sales = $sales;

        return $this;
    }
}
