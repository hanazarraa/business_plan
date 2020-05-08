<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvestmentsRepository")
 */
class Investments
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
     * @ORM\Column(type="json")
     */
    private $tvalist = [];

    /**
     * @ORM\Column(type="json")
     */
    private $duration = [];

    /**
     * @ORM\Column(type="json")
     */
    private $categorie = [];

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
     * @ORM\OneToMany(targetEntity="App\Entity\Investmentsdetail", mappedBy="Investment")
     */
    private $investmentsdetails;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Businessplan", cascade={"persist", "remove"})
     */
    private $businessplan;

    public function __construct()
    {
        $this->investmentsdetails = new ArrayCollection();
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

   

    public function getTvalist(): ?array
    {
        return $this->tvalist;
    }

    public function setTvalist(array $tvalist): self
    {
        $this->tvalist = $tvalist;

        return $this;
    }

    public function getDuration(): ?array
    {
        return $this->duration;
    }

    public function setDuration(array $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getCategorie(): ?array
    {
        return $this->categorie;
    }

    public function setCategorie(array $categorie): self
    {
        $this->categorie = $categorie;

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

    /**
     * @return Collection|Investmentsdetail[]
     */
    public function getInvestmentsdetails(): Collection
    {
        return $this->investmentsdetails;
    }

    public function addInvestmentsdetail(Investmentsdetail $investmentsdetail): self
    {
        if (!$this->investmentsdetails->contains($investmentsdetail)) {
            $this->investmentsdetails[] = $investmentsdetail;
            $investmentsdetail->setInvestment($this);
        }

        return $this;
    }

    public function removeInvestmentsdetail(Investmentsdetail $investmentsdetail): self
    {
        if ($this->investmentsdetails->contains($investmentsdetail)) {
            $this->investmentsdetails->removeElement($investmentsdetail);
            // set the owning side to null (unless already changed)
            if ($investmentsdetail->getInvestment() === $this) {
                $investmentsdetail->setInvestment(null);
            }
        }

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
