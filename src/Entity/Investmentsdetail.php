<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvestmentsdetailRepository")
 */
class Investmentsdetail
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Investments", inversedBy="investmentsdetails")
     */
    private $Investment;

    /**
     * @ORM\Column(type="json")
     */
    private $Administration = [];

    /**
     * @ORM\Column(type="json")
     */
    private $production = [];

    /**
     * @ORM\Column(type="json")
     */
    private $sales = [];

    /**
     * @ORM\Column(type="json")
     */
    private $recherche = [];

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

    public function getInvestment(): ?Investments
    {
        return $this->Investment;
    }

    public function setInvestment(?Investments $Investment): self
    {
        $this->Investment = $Investment;

        return $this;
    }

    public function getAdministration(): ?array
    {
        return $this->Administration;
    }

    public function setAdministration(array $Administration): self
    {
        $this->Administration = $Administration;

        return $this;
    }

    public function getProduction(): ?array
    {
        return $this->production;
    }

    public function setProduction(array $production): self
    {
        $this->production = $production;

        return $this;
    }

    public function getSales(): ?array
    {
        return $this->sales;
    }

    public function setSales(array $sales): self
    {
        $this->sales = $sales;

        return $this;
    }

    public function getRecherche(): ?array
    {
        return $this->recherche;
    }

    public function setRecherche(array $recherche): self
    {
        $this->recherche = $recherche;

        return $this;
    }
}
