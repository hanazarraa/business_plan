<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Product;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UnitInovicingRepository")
 */
class UnitInovicing 
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json",nullable=true)
     */
    private $sellprice = [];

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vat;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $product_reciept_rule = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $product_cost_sales = [];

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vat_purchases;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $purchase_disbursment_rule = [];

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Product", inversedBy="unitInovicing", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSellprice(): ?array
    {
        return $this->sellprice;
    }

    public function setSellprice(array $sellprice): self
    {
        $this->sellprice = $sellprice;

        return $this;
    }

    public function getVat(): ?float
    {
        return $this->vat;
    }

    public function setVat(?float $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    public function getProductRecieptRule(): ?array
    {
        return $this->product_reciept_rule;
    }

    public function setProductRecieptRule(?array $product_reciept_rule): self
    {
        $this->product_reciept_rule = $product_reciept_rule;

        return $this;
    }

    public function getProductCostSales(): ?array
    {
        return $this->product_cost_sales;
    }

    public function setProductCostSales(?array $product_cost_sales): self
    {
        $this->product_cost_sales = $product_cost_sales;

        return $this;
    }

    public function getVatPurchases(): ?float
    {
        return $this->vat_purchases;
    }

    public function setVatPurchases(?float $vat_purchases): self
    {
        $this->vat_purchases = $vat_purchases;

        return $this;
    }

    public function getPurchaseDisbursmentRule(): ?array
    {
        return $this->purchase_disbursment_rule;
    }

    public function setPurchaseDisbursmentRule(?array $purchase_disbursment_rule): self
    {
        $this->purchase_disbursment_rule = $purchase_disbursment_rule;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
