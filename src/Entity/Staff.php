<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StaffRepository")
 */
class Staff
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
    private $Administration = [];

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Businessplan", cascade={"persist", "remove"})
     */
    private $businessplan;

    /**
     * @ORM\Column(type="json")
     */
    private $Production = [];

    /**
     * @ORM\Column(type="json")
     */
    private $Sales = [];

    /**
     * @ORM\Column(type="json")
     */
    private $Recherche = [];

    /**
     * @ORM\Column(type="json")
     */
    private $Salairebrut = [];

    /**
     * @ORM\Column(type="json")
     */
    private $parametre = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Staffdetail", mappedBy="staff")
     */
    private $staffdetails;

    /**
     * @ORM\Column(type="json")
     */
    private $conditions = [];

    /**
     * @ORM\Column(type="json")
     */
    private $charges = [];

    public function __construct()
    {
        $this->staffdetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBusinessplan(): ?Businessplan
    {
        return $this->businessplan;
    }

    public function setBusinessplan(?Businessplan $businessplan): self
    {
        $this->businessplan = $businessplan;

        return $this;
    }

    public function getProduction(): ?array
    {
        return $this->Production;
    }

    public function setProduction(array $Production): self
    {
        $this->Production = $Production;

        return $this;
    }

    public function getSales(): ?array
    {
        return $this->Sales;
    }

    public function setSales(array $Sales): self
    {
        $this->Sales = $Sales;

        return $this;
    }

    public function getRecherche(): ?array
    {
        return $this->Recherche;
    }

    public function setRecherche(array $Recherche): self
    {
        $this->Recherche = $Recherche;

        return $this;
    }

    public function getSalairebrut(): ?array
    {
        return $this->Salairebrut;
    }

    public function setSalairebrut(array $Salairebrut): self
    {
        $this->Salairebrut = $Salairebrut;

        return $this;
    }

    public function getParametre(): ?array
    {
        return $this->parametre;
    }

    public function setParametre(array $parametre): self
    {
        $this->parametre = $parametre;

        return $this;
    }

    /**
     * @return Collection|Staffdetail[]
     */
    public function getStaffdetails(): Collection
    {
        return $this->staffdetails;
    }

    public function addStaffdetail(Staffdetail $staffdetail): self
    {
        if (!$this->staffdetails->contains($staffdetail)) {
            $this->staffdetails[] = $staffdetail;
            $staffdetail->setStaff($this);
        }

        return $this;
    }

    public function removeStaffdetail(Staffdetail $staffdetail): self
    {
        if ($this->staffdetails->contains($staffdetail)) {
            $this->staffdetails->removeElement($staffdetail);
            // set the owning side to null (unless already changed)
            if ($staffdetail->getStaff() === $this) {
                $staffdetail->setStaff(null);
            }
        }

        return $this;
    }

    public function getConditions(): ?array
    {
        return $this->conditions;
    }

    public function setConditions(array $conditions): self
    {
        $this->conditions = $conditions;

        return $this;
    }

    public function getCharges(): ?array
    {
        return $this->charges;
    }

    public function setCharges(array $charges): self
    {
        $this->charges = $charges;

        return $this;
    }
}
