<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Regex;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints as CaptchaAssert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 */

class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
     
 /**
     * @Assert\Regex(
     *  pattern     = "/^[a-zA-Z]+[0-9]*@[a-zA-Z]+.[a-zA-Z]+$/i",
     *     htmlPattern = "^[a-zA-Z]+[09]*@[a-zA-Z]+.[a-zA-Z]+$",
     *     message="Your name cannot contain a number"
     * )
     */
    /**
     * @Assert\Unique(message="The {{ value }} email is repeated.")
     */
    /**
     * @ORM\Column(type="string", length=180,unique=true)
     * 
     */
   
    

    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

  /**
   * @CaptchaAssert\ValidCaptcha(
   *   message = "validation failed , try again",
   *   groups={"registration"}
   * ) */  
  protected $captchaCode;

  public function getCaptchaCode()
  {
    return $this->captchaCode;
  }

  public function setCaptchaCode($captchaCode)
  {
    $this->captchaCode = $captchaCode;
  }
   

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Businessplan", mappedBy="user")
     */
    private $businessplans;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $agreeTerms;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ConfirmationToken;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $tokenpassword;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $createdtokenpassword;

    
    public function __construct()
    {
        $this->businessplans = new ArrayCollection();
        $this->enabled=false;
       
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
      //  $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    
    /**
     * @return Collection|Businessplan[]
     */
    public function getBusinessplans(): Collection
    {
        return $this->businessplans;
    }

    public function addBusinessplan(Businessplan $businessplan): self
    {
        if (!$this->businessplans->contains($businessplan)) {
            $this->businessplans[] = $businessplan;
            $businessplan->setUser($this);
        }

        return $this;
    }

    public function removeBusinessplan(Businessplan $businessplan): self
    {
        if ($this->businessplans->contains($businessplan)) {
            $this->businessplans->removeElement($businessplan);
            // set the owning side to null (unless already changed)
            if ($businessplan->getUser() === $this) {
                $businessplan->setUser(null);
            }
        }

        return $this;
    }

    public function getAgreeTerms(): ?bool
    {
        return $this->agreeTerms;
    }

    public function setAgreeTerms(?bool $agreeTerms): self
    {
        $this->agreeTerms = $agreeTerms;

        return $this;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->ConfirmationToken;
    }

    public function setConfirmationToken(string $ConfirmationToken): self
    {
        $this->ConfirmationToken = $ConfirmationToken;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getTokenpassword(): ?string
    {
        return $this->tokenpassword;
    }

    public function setTokenpassword(string $tokenpassword): self
    {
        $this->tokenpassword = $tokenpassword;

        return $this;
    }

    public function getCreatedtokenpassword(): ?\DateTimeInterface
    {
        return $this->createdtokenpassword;
    }

    public function setCreatedtokenpassword(\DateTimeInterface $createdtokenpassword): self
    {
        $this->createdtokenpassword = $createdtokenpassword;

        return $this;
    }

   
  

}