<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SalesRepository")
 */
class Sales
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Salesdetailled", mappedBy="sales")
     */
    private $detail;

    public function __construct()
    {
        $this->detail = new ArrayCollection();
    }


    





    
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|salesdetailled[]
     */
    public function getDetail(): Collection
    {
        return $this->detail;
    }

    public function addDetail(salesdetailled $detail): self
    {
        if (!$this->detail->contains($detail)) {
            $this->detail[] = $detail;
            $detail->setSales($this);
        }

        return $this;
    }

    public function removeDetail(salesdetailled $detail): self
    {
        if ($this->detail->contains($detail)) {
            $this->detail->removeElement($detail);
            // set the owning side to null (unless already changed)
            if ($detail->getSales() === $this) {
                $detail->setSales(null);
            }
        }

        return $this;
    }



    

  

    
}
