<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StaffdetailRepository")
 */
class Staffdetail
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
    private $administration = [];

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

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\staff", inversedBy="staffdetails")
     */
    private $staff;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdministration(): ?array
    {
        return $this->administration;
    }

    public function setAdministration(array $administration): self
    {
        $this->administration = $administration;

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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getStaff(): ?staff
    {
        return $this->staff;
    }

    public function setStaff(?staff $staff): self
    {
        $this->staff = $staff;

        return $this;
    }
}
