<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\HasLifecycleCallbacks
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
     * @Assert\Positive
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
     * @Assert\Range(min =0 , max = 100)
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

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $UpdatedAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Sales", cascade={"persist", "remove"})
     */
    private $sales;

    /**
     * @ORM\Column(type="integer")
     */
    private $rangeofdetail;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Loans", mappedBy="businessplan")
     */
    private $loans;



   

    function __construct()
    {
        $this->investments = new ArrayCollection();
        $this->loans = new ArrayCollection();
    }
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->UpdatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $UpdatedAt): self
    {
        $this->UpdatedAt = $UpdatedAt;

        return $this;
    }
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
public function updatedTimestamps(): void
{
    $this->setUpdatedAt(new \DateTime('now'));    
    if ($this->getCreatedAt() === null) {
        $this->setCreatedAt(new \DateTime('now'));
    }
}

public function getSales(): ?sales
{
    return $this->sales;
}

public function setSales(?sales $sales): self
{
    $this->sales = $sales;

    return $this;
}

public function getRangeofdetail(): ?int
{
    return $this->rangeofdetail;
}

public function setRangeofdetail(int $rangeofdetail): self
{
    $this->rangeofdetail = $rangeofdetail;

    return $this;
}

/**
 * @return Collection|Loans[]
 */
public function getLoans(): Collection
{
    return $this->loans;
}

public function addLoan(Loans $loan): self
{
    if (!$this->loans->contains($loan)) {
        $this->loans[] = $loan;
        $loan->setBusinessplan($this);
    }

    return $this;
}

public function removeLoan(Loans $loan): self
{
    if ($this->loans->contains($loan)) {
        $this->loans->removeElement($loan);
        // set the owning side to null (unless already changed)
        if ($loan->getBusinessplan() === $this) {
            $loan->setBusinessplan(null);
        }
    }

    return $this;
}



}
