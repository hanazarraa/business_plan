<?php

namespace App\Entity;
use App\Entity\Product;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


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
     * @ORM\Column(type="json")
     */
    protected $sellsprice = [];

    /**
     * @Assert\Range(min =0 , max = 100)
     * @ORM\Column(type="float",nullable=true)
     */
    protected $vat;

    /**
     * @ORM\Column(type="json")
     * 
     */
    protected $products_reciept_rule = [];

    /**
     * @ORM\Column(type="json")
     */
    protected $product_cost_sales = [];

    /**
     * @ORM\Column(type="float",nullable=true)
     * @Assert\Range(min =0 , max = 100)
     */
    protected $vat_purchases;

    /**
     * @ORM\Column(type="json")
     */
    protected $purchase_disbursment_rule = [];

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

    public function setVat(float $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    public function getProductsRecieptRule(): ?array
    {
        return $this->products_reciept_rule;
    }

    public function setProductsRecieptRule(array $products_reciept_rule): self
    {
        $this->products_reciept_rule = $products_reciept_rule;

        return $this;
    }

    public function getProductCostSales(): ?array
    {
        return $this->product_cost_sales;
    }

    public function setProductCostSales(array $product_cost_sales): self
    {
        $this->product_cost_sales = $product_cost_sales;

        return $this;
    }

    public function getVatPurchases(): ?float
    {
        return $this->vat_purchases;
    }

    public function setVatPurchases(float $vat_purchases): self
    {
        $this->vat_purchases = $vat_purchases;

        return $this;
    }

    public function getPurchaseDisbursmentRule(): ?array
    {
        return $this->purchase_disbursment_rule;
    }

    public function setPurchaseDisbursmentRule(array $purchase_disbursment_rule): self
    {
        $this->purchase_disbursment_rule = $purchase_disbursment_rule;

        return $this;
    }
    public function __toString(){
        return "Unit Invoicing";
    }
}
