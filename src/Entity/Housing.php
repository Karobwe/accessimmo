<?php

namespace App\Entity;

use App\Repository\HousingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HousingRepository::class)
 */
class Housing
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shortDescription;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="smallint")
     */
    private $roomCount;

    /**
     * @ORM\Column(type="smallint")
     */
    private $bedroomCount;

    /**
     * @ORM\Column(type="float")
     */
    private $floorArea;

    /**
     * @ORM\ManyToOne(targetEntity=Address::class, inversedBy="housings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="housings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="housings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="housing", orphanRemoval=true)
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="Housing")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getRoomCount(): ?int
    {
        return $this->roomCount;
    }

    public function setRoomCount(int $roomCount): self
    {
        $this->roomCount = $roomCount;

        return $this;
    }

    public function getBedroomCount(): ?int
    {
        return $this->bedroomCount;
    }

    public function setBedroomCount(int $bedroomCount): self
    {
        $this->bedroomCount = $bedroomCount;

        return $this;
    }

    public function getFloorArea(): ?float
    {
        return $this->floorArea;
    }

    public function setFloorArea(float $floorArea): self
    {
        $this->floorArea = $floorArea;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function __toString()
    {
        return $this->getType()->getName() . ' ' . 
            $this->getRoomCount() . ' rooms ' . 
            $this->getFloorArea() . 'm² ' . 
            $this->getPrice() . '€ ' . 
            $this->getAddress()->getCity();
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setHousing($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getHousing() === $this) {
                $image->setHousing(null);
            }
        }

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
}
