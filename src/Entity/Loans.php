<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\LoansRepository")
 */
class Loans
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
     * @ORM\Column(type="date")
     */
    private $loandate;

    /**
     * @ORM\Column(type="date")
     * @Assert\GreaterThan(propertyPath="loandate")
     */
    private $firstpaymentdate;

    /**
     * @ORM\Column(type="float")
     * @Assert\PositiveOrZero
     */
    private $amount;

    /**
     * @ORM\Column(type="float")
     * @Assert\PositiveOrZero
     */
    private $taux;

    /**
     * @ORM\Column(type="integer")
     *  @Assert\PositiveOrZero
     */
    private $duration;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numberofpayment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\businessplan", inversedBy="loans")
     */
    private $businessplan;

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

    public function getLoandate(): ?\DateTimeInterface
    {
        return $this->loandate;
    }

    public function setLoandate(\DateTimeInterface $loandate): self
    {
        $this->loandate = $loandate;

        return $this;
    }

    public function getFirstpaymentdate(): ?\DateTimeInterface
    {
        return $this->firstpaymentdate;
    }

    public function setFirstpaymentdate(\DateTimeInterface $firstpaymentdate): self
    {
        $this->firstpaymentdate = $firstpaymentdate;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getTaux(): ?float
    {
        return $this->taux;
    }

    public function setTaux(float $taux): self
    {
        $this->taux = $taux;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getNumberofpayment(): ?string
    {
        return $this->numberofpayment;
    }

    public function setNumberofpayment(string $numberofpayment): self
    {
        $this->numberofpayment = $numberofpayment;

        return $this;
    }

    public function getBusinessplan(): ?businessplan
    {
        return $this->businessplan;
    }

    public function setBusinessplan(?businessplan $businessplan): self
    {
        $this->businessplan = $businessplan;

        return $this;
    }
}
