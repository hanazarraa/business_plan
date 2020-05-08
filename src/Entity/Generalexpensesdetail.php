<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GeneralexpensesdetailRepository")
 */
class Generalexpensesdetail
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
    private $detail = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Generalexpenses", inversedBy="expensesdetail")
     */
    private $generalexpenses;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="json")
     */
    private $DetailProduction = [];

    /**
     * @ORM\Column(type="json")
     */
    private $DetailCommercial = [];

    /**
     * @ORM\Column(type="json")
     */
    private $DetailRecherche = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDetail(): ?array
    {
        return $this->detail;
    }

    public function setDetail(?array $detail): self
    {
        $this->detail = $detail;

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

    public function getGeneralexpenses(): ?Generalexpenses
    {
        return $this->generalexpenses;
    }

    public function setGeneralexpenses(?Generalexpenses $generalexpenses): self
    {
        $this->generalexpenses = $generalexpenses;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDetailProduction(): ?array
    {
        return $this->DetailProduction;
    }

    public function setDetailProduction(array $DetailProduction): self
    {
        $this->DetailProduction = $DetailProduction;

        return $this;
    }

    public function getDetailCommercial(): ?array
    {
        return $this->DetailCommercial;
    }

    public function setDetailCommercial(array $DetailCommercial): self
    {
        $this->DetailCommercial = $DetailCommercial;

        return $this;
    }

    public function getDetailRecherche(): ?array
    {
        return $this->DetailRecherche;
    }

    public function setDetailRecherche(array $DetailRecherche): self
    {
        $this->DetailRecherche = $DetailRecherche;

        return $this;
    }
}
