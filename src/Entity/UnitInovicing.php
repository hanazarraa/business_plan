<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Product;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UnitInovicingRepository")
 */
class UnitInovicing extends Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="json",nullable=true)
     */
    private $sellsprice = [0,0,0];

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vat;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $products_reciept_rule = [
     ['Cash'=>0,'30 days'=>0,'90 days'=>0,'120 days'=>0]
    ,['Cash'=>0,'30 days'=>0,'90 days'=>0,'120 days'=>0],
    ['Cash'=>0,'30 days'=>0,'90 days'=>0,'120 days'=>0]
];

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

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSellsprice(): ?array
    {
        return $this->sellsprice;
    }

    public function setSellsprice(array $sellsprice): self
    {
        $this->sellsprice = $sellsprice;

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

    public function getProductsRecieptRule(): ?array
    {
        return $this->products_reciept_rule;
    }

    public function setProductRecieptRule(?array $products_reciept_rule): self
    {
        $this->products_reciept_rule = $products_reciept_rule;

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

   
}
