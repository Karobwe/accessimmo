<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity=Housing::class, mappedBy="user", orphanRemoval=true)
     */
    private $Housing;

    public function __construct()
    {
        $this->Housing = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getRoles(): ?string
    {
        return $this->roles;
    }

    public function setRoles(string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|Housing[]
     */
    public function getHousing(): Collection
    {
        return $this->Housing;
    }

    public function addHousing(Housing $housing): self
    {
        if (!$this->Housing->contains($housing)) {
            $this->Housing[] = $housing;
            $housing->setUser($this);
        }

        return $this;
    }

    public function removeHousing(Housing $housing): self
    {
        if ($this->Housing->contains($housing)) {
            $this->Housing->removeElement($housing);
            // set the owning side to null (unless already changed)
            if ($housing->getUser() === $this) {
                $housing->setUser(null);
            }
        }

        return $this;
    }
}
