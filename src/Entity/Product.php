<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Businessplan", inversedBy="products")
     */
    private $businessplan;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UnitInovicing", mappedBy="product", cascade={"persist", "remove"})
     */
    private $unitInovicing;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getUnitInovicing(): ?UnitInovicing
    {
        return $this->unitInovicing;
    }

    public function setUnitInovicing(UnitInovicing $unitInovicing): self
    {
        $this->unitInovicing = $unitInovicing;

        // set the owning side of the relation if necessary
        if ($unitInovicing->getProduct() !== $this) {
            $unitInovicing->setProduct($this);
        }

        return $this;
    }
}
