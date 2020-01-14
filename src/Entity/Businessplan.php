<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BusinessplanRepository")
 */
class Businessplan
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="businessplans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeofbusiness;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $currency;

    /**
     * @ORM\Column(type="integer")
     */
    private $startyear;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $startmonth;

    /**
     * @ORM\Column(type="boolean")
     */
    private $includeitems;

    /**
     * @ORM\Column(type="float")
     */
    private $defaultVAT;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Companyname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numberofyears;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
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

    public function getTypeofbusiness(): ?string
    {
        return $this->typeofbusiness;
    }

    public function setTypeofbusiness(string $typeofbusiness): self
    {
        $this->typeofbusiness = $typeofbusiness;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getStartyear(): ?int
    {
        return $this->startyear;
    }

    public function setStartyear(int $startyear): self
    {
        $this->startyear = $startyear;

        return $this;
    }

    public function getStartmonth(): ?string
    {
        return $this->startmonth;
    }

    public function setStartmonth(string $startmonth): self
    {
        $this->startmonth = $startmonth;

        return $this;
    }

    public function getIncludeitems(): ?bool
    {
        return $this->includeitems;
    }

    public function setIncludeitems(bool $includeitems): self
    {
        $this->includeitems = $includeitems;

        return $this;
    }

    public function getDefaultVAT(): ?float
    {
        return $this->defaultVAT;
    }

    public function setDefaultVAT(float $defaultVAT): self
    {
        $this->defaultVAT = $defaultVAT;

        return $this;
    }

    public function getCompanyname(): ?string
    {
        return $this->Companyname;
    }

    public function setCompanyname(?string $Companyname): self
    {
        $this->Companyname = $Companyname;

        return $this;
    }

    public function getNumberofyears(): ?string
    {
        return $this->numberofyears;
    }

    public function setNumberofyears(string $numberofyears): self
    {
        $this->numberofyears = $numberofyears;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $Code): self
    {
        $this->code = $Code;

        return $this;
    }
}
