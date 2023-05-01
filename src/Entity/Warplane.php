<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\WarplaneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WarplaneRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Put(),
        new Delete(),
    ]
)]
class Warplane
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $armament = null;

    #[ORM\OneToMany(mappedBy: 'assignedPlane', targetEntity: FlightSchedule::class)]
    private Collection $flightSchedules;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->flightSchedules = new ArrayCollection();
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

    public function getArmament(): ?string
    {
        return $this->armament;
    }

    public function setArmament(string $armament): self
    {
        $this->armament = $armament;

        return $this;
    }

    /**
     * @return Collection<int, FlightSchedule>
     */
    public function getFlightSchedules(): Collection
    {
        return $this->flightSchedules;
    }

    public function addFlightSchedule(FlightSchedule $flightSchedule): self
    {
        if (!$this->flightSchedules->contains($flightSchedule)) {
            $this->flightSchedules->add($flightSchedule);
            $flightSchedule->setAssignedPlane($this);
        }

        return $this;
    }

    public function removeFlightSchedule(FlightSchedule $flightSchedule): self
    {
        if ($this->flightSchedules->removeElement($flightSchedule)) {
            // set the owning side to null (unless already changed)
            if ($flightSchedule->getAssignedPlane() === $this) {
                $flightSchedule->setAssignedPlane(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
