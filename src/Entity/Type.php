<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeRepository::class)
 */
class Type
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Housing::class, mappedBy="type")
     */
    private $housings;

    public function __construct()
    {
        $this->housings = new ArrayCollection();
    }

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

    /**
     * @return Collection|Housing[]
     */
    public function getHousings(): Collection
    {
        return $this->housings;
    }

    public function addHousing(Housing $housing): self
    {
        if (!$this->housings->contains($housing)) {
            $this->housings[] = $housing;
            $housing->setType($this);
        }

        return $this;
    }

    public function removeHousing(Housing $housing): self
    {
        if ($this->housings->contains($housing)) {
            $this->housings->removeElement($housing);
            // set the owning side to null (unless already changed)
            if ($housing->getType() === $this) {
                $housing->setType(null);
            }
        }

        return $this;
    }
<<<<<<< HEAD
=======

    public function __toString()
    {
        return $this->getName();
    }
>>>>>>> 0ab96d1acdb706e1657912bf9e86be8e82eeddc8
}
