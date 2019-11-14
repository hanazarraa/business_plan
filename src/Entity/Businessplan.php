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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_periodes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $devise;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $format;

    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mois_debut;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $societe_elements_anterieurs;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $tva;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_societe;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="businessplans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $global_only;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $annee_debut;

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

    public function getNbPeriodes(): ?int
    {
        return $this->nb_periodes;
    }

    public function setNbPeriodes(int $nb_periodes): self
    {
        $this->nb_periodes = $nb_periodes;

        return $this;
    }

    public function getDevise(): ?string
    {
        return $this->devise;
    }

    public function setDevise(string $devise): self
    {
        $this->devise = $devise;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

  
    public function getMoisDebut(): ?string
    {
        return $this->mois_debut;
    }

    public function setMoisDebut(string $mois_debut): self
    {
        $this->mois_debut = $mois_debut;

        return $this;
    }

    public function getSocieteElementsAnterieurs(): ?string
    {
        return $this->societe_elements_anterieurs;
    }

    public function setSocieteElementsAnterieurs(string $societe_elements_anterieurs): self
    {
        $this->societe_elements_anterieurs = $societe_elements_anterieurs;

        return $this;
    }

    public function getTva(): ?string
    {
        return $this->tva;
    }

    public function setTva(string $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    public function getNomSociete(): ?string
    {
        return $this->nom_societe;
    }

    public function setNomSociete(string $nom_societe): self
    {
        $this->nom_societe = $nom_societe;

        return $this;
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

    public function getGlobalOnly(): ?string
    {
        return $this->global_only;
    }

    public function setGlobalOnly(string $global_only): self
    {
        $this->global_only = $global_only;

        return $this;
    }

    public function getAnneeDebut(): ?string
    {
        return $this->annee_debut;
    }

    public function setAnneeDebut(string $annee_debut): self
    {
        $this->annee_debut = $annee_debut;

        return $this;
    }
}
