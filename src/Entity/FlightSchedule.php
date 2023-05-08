<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\FlightScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FlightScheduleRepository::class)]
#[ApiResource(
    operations: [
        new Get(security: 'is_granted("ROLE_USER")'),
        new GetCollection(security: 'is_granted("ROLE_USER")'),
        new Post(security: 'is_granted("ROLE_USER")'),
        new Patch(security: 'is_granted("ROLE_USER")'),
        new Delete(security: 'is_granted("ROLE_USER")'),
    ],
    paginationItemsPerPage: 20,
)]
class FlightSchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $idFlight = null;

    #[ORM\ManyToOne(inversedBy: 'flightSchedules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Warplane $assignedPlane = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $departureTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $arrivalTime = null;

    #[ORM\OneToMany(mappedBy: 'flightScheduleDeparture', targetEntity: Track::class)]
    private Collection $departureTrack;

    #[ORM\OneToMany(mappedBy: 'flightScheduleArrival', targetEntity: Track::class)]
    private Collection $arrivalTrack;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->departureTrack = new ArrayCollection();
        $this->arrivalTrack = new ArrayCollection();
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

    public function getIdFlight(): ?string
    {
        return $this->idFlight;
    }

    public function setIdFlight(string $idFlight): self
    {
        $this->idFlight = $idFlight;

        return $this;
    }

    public function getAssignedPlane(): ?Warplane
    {
        return $this->assignedPlane;
    }

    public function setAssignedPlane(?Warplane $assignedPlane): self
    {
        $this->assignedPlane = $assignedPlane;

        return $this;
    }

    public function getDepartureTime(): ?\DateTimeInterface
    {
        return $this->departureTime;
    }

    public function setDepartureTime(\DateTimeInterface $departureTime): self
    {
        $this->departureTime = $departureTime;

        return $this;
    }

    public function getArrivalTime(): ?\DateTimeInterface
    {
        return $this->arrivalTime;
    }

    public function setArrivalTime(\DateTimeInterface $arrivalTime): self
    {
        $this->arrivalTime = $arrivalTime;

        return $this;
    }

    /**
     * @return Collection<int, Track>
     */
    public function getDepartureTrack(): Collection
    {
        return $this->departureTrack;
    }

    public function addDepartureTrack(Track $departureTrack): self
    {
        if (!$this->departureTrack->contains($departureTrack)) {
            $this->departureTrack->add($departureTrack);
            $departureTrack->setFlightScheduleDeparture($this);
        }

        return $this;
    }

    public function removeDepartureTrack(Track $departureTrack): self
    {
        if ($this->departureTrack->removeElement($departureTrack)) {
            // set the owning side to null (unless already changed)
            if ($departureTrack->getFlightScheduleDeparture() === $this) {
                $departureTrack->setFlightScheduleDeparture(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Track>
     */
    public function getArrivalTrack(): Collection
    {
        return $this->arrivalTrack;
    }

    public function addArrivalTrack(Track $arrivalTrack): self
    {
        if (!$this->arrivalTrack->contains($arrivalTrack)) {
            $this->arrivalTrack->add($arrivalTrack);
            $arrivalTrack->setFlightScheduleArrival($this);
        }

        return $this;
    }

    public function removeArrivalTrack(Track $arrivalTrack): self
    {
        if ($this->arrivalTrack->removeElement($arrivalTrack)) {
            // set the owning side to null (unless already changed)
            if ($arrivalTrack->getFlightScheduleArrival() === $this) {
                $arrivalTrack->setFlightScheduleArrival(null);
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
