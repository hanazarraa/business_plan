<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ReccuringInvoicingRepository")
 */
class ReccuringInvoicing extends Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;


    /**
     * @ORM\Column(type="float")
     */
    protected $saleprice;

    /**
     * @ORM\Column(type="float")
     * @Assert\Range(min =0 , max = 100)
     */
    protected $vat;

    /**
     * @ORM\Column(type="float")
     */
    protected $purchasecostofsales;

    /**
     * @ORM\Column(type="float")
     */
    protected $vatonpurchases;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $periodicity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $firstoccurence;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $permanent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numberofoccurences;

    public function getId(): ?int
    {
        return $this->id;
    }

  

    public function getSaleprice(): ?float
    {
        return $this->saleprice;
    }

    public function setSaleprice(float $saleprice): self
    {
        $this->saleprice = $saleprice;

        return $this;
    }

    public function getVat(): ?float
    {
        return $this->vat;
    }

    public function setVat(float $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    public function getPurchasecostofsales(): ?float
    {
        return $this->purchasecostofsales;
    }

    public function setPurchasecostofsales(float $purchasecostofsales): self
    {
        $this->purchasecostofsales = $purchasecostofsales;

        return $this;
    }

    public function getVatonpurchases(): ?float
    {
        return $this->vatonpurchases;
    }

    public function setVatonpurchases(float $vatonpurchases): self
    {
        $this->vatonpurchases = $vatonpurchases;

        return $this;
    }

    public function getPeriodicity(): ?string
    {
        return $this->periodicity;
    }

    public function setPeriodicity(string $periodicity): self
    {
        $this->periodicity = $periodicity;

        return $this;
    }

    public function getFirstoccurence(): ?string
    {
        return $this->firstoccurence;
    }

    public function setFirstoccurence(string $firstoccurence): self
    {
        $this->firstoccurence = $firstoccurence;

        return $this;
    }

    public function getPermanent(): ?bool
    {
        return $this->permanent;
    }

    public function setPermanent(bool $permanent): self
    {
        $this->permanent = $permanent;

        return $this;
    }
     
    public function __toString(){

        return 'Reccuring Invoicing';
    }

    public function getNumberofoccurences(): ?int
    {
        return $this->numberofoccurences;
    }

    public function setNumberofoccurences(?int $numberofoccurences): self
    {
        $this->numberofoccurences = $numberofoccurences;
        
        return $this;
    }

}
