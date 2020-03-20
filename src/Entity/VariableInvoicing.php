<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Product;
/**
 * @ORM\Entity(repositoryClass="App\Repository\VariableInvoicingRepository")
 */
class VariableInvoicing extends Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;



    /**
     * @ORM\Column(type="float")
     * @Assert\Range(min =0 , max = 100)
     */
    protected $vat;

    /**
     * @ORM\Column(type="json", nullable=true)
     * 
     */
    protected $productreceipt = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    protected $purchasecostofsales = [];

    /**
     * @ORM\Column(type="float")
     * @Assert\Range(min =0 , max = 100)
     */
    protected $vatonpurchase;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    protected $purchasedisbursement = [];

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProductreceipt(): ?array
    {
        return $this->productreceipt;
    }

    public function setProductreceipt(?array $productreceipt): self
    {
        $this->productreceipt = $productreceipt;

        return $this;
    }

    public function getPurchasecostofsales(): ?array
    {
        return $this->purchasecostofsales;
    }

    public function setPurchasecostofsales(?array $purchasecostofsales): self
    {
        $this->purchasecostofsales = $purchasecostofsales;

        return $this;
    }

    public function getVatonpurchase(): ?float
    {
        return $this->vatonpurchase;
    }

    public function setVatonpurchase(float $vatonpurchase): self
    {
        $this->vatonpurchase = $vatonpurchase;

        return $this;
    }

    public function getPurchasedisbursement(): ?array
    {
        return $this->purchasedisbursement;
    }

    public function setPurchasedisbursement(?array $purchasedisbursement): self
    {
        $this->purchasedisbursement = $purchasedisbursement;

        return $this;
    }
    public function __toString(){
    return "Variable Invoicing";
    }
}
