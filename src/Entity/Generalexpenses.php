<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GeneralexpensesRepository")
 */
class Generalexpenses
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
    private $administration = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $production = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $sales = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $research = [];

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Businessplan", cascade={"persist", "remove"})
     */
    private $businessplan;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Generalexpensesdetail", mappedBy="generalexpenses")
     */
    private $expensesdetail;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $TVAlist = [];

    public function __construct()
    {
        $this->expensesdetail = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdministration(): ?array
    {
        return $this->administration;
    }

    public function setAdministration(?array $administration): self
    {
        $this->administration = $administration;

        return $this;
    }

    public function getProduction(): ?array
    {
        return $this->production;
    }

    public function setProduction(?array $production): self
    {
        $this->production = $production;

        return $this;
    }

    public function getSales(): ?array
    {
        return $this->sales;
    }

    public function setSales(?array $sales): self
    {
        $this->sales = $sales;

        return $this;
    }

    public function getResearch(): ?array
    {
        return $this->research;
    }

    public function setResearch(?array $research): self
    {
        $this->research = $research;

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

    /**
     * @return Collection|Generalexpensesdetail[]
     */
    public function getExpensesdetail(): Collection
    {
        return $this->expensesdetail;
    }

    public function addExpensesdetail(Generalexpensesdetail $expensesdetail): self
    {
        if (!$this->expensesdetail->contains($expensesdetail)) {
            $this->expensesdetail[] = $expensesdetail;
            $expensesdetail->setGeneralexpenses($this);
        }

        return $this;
    }

    public function removeExpensesdetail(Generalexpensesdetail $expensesdetail): self
    {
        if ($this->expensesdetail->contains($expensesdetail)) {
            $this->expensesdetail->removeElement($expensesdetail);
            // set the owning side to null (unless already changed)
            if ($expensesdetail->getGeneralexpenses() === $this) {
                $expensesdetail->setGeneralexpenses(null);
            }
        }

        return $this;
    }

    public function getTVAlist(): ?array
    {
        return $this->TVAlist;
    }

    public function setTVAlist(?array $TVAlist): self
    {
        $this->TVAlist = $TVAlist;

        return $this;
    }
}
