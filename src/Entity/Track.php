<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\TrackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrackRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Put(),
        new Delete(),
    ],
    paginationItemsPerPage: 20,
)]
class Track
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idTrackNumber = null;

    #[ORM\Column(name: 'track_name_qfu',length: 50)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'numTrack')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Airport $airport = null;

    #[ORM\ManyToOne(inversedBy: 'departureTrack')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FlightSchedule $flightScheduleDeparture = null;

    #[ORM\ManyToOne(inversedBy: 'arrivalTrack')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FlightSchedule $flightScheduleArrival = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $positionTrack = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hasTerminal = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $terminalName = null;

    #[ORM\Column(nullable: true)]
    private ?int $terminalNumber = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdTrackNumber(): ?int
    {
        return $this->idTrackNumber;
    }

    public function setIdTrackNumber(int $idTrackNumber): self
    {
        $this->idTrackNumber = $idTrackNumber;

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

    public function getAirport(): ?Airport
    {
        return $this->airport;
    }

    public function setAirport(?Airport $airport): self
    {
        $this->airport = $airport;

        return $this;
    }

    public function getFlightScheduleDeparture(): ?FlightSchedule
    {
        return $this->flightScheduleDeparture;
    }

    public function setFlightScheduleDeparture(?FlightSchedule $flightScheduleDeparture): self
    {
        $this->flightScheduleDeparture = $flightScheduleDeparture;

        return $this;
    }

    public function getFlightScheduleArrival(): ?FlightSchedule
    {
        return $this->flightScheduleArrival;
    }

    public function setFlightScheduleArrival(?FlightSchedule $flightScheduleArrival): self
    {
        $this->flightScheduleArrival = $flightScheduleArrival;

        return $this;
    }

    public function getPositionTrack(): ?string
    {
        return $this->positionTrack;
    }

    public function setPositionTrack(?string $positionTrack): self
    {
        $this->positionTrack = $positionTrack;

        return $this;
    }

    public function isHasTerminal(): ?bool
    {
        return $this->hasTerminal;
    }

    public function setHasTerminal(?bool $hasTerminal): self
    {
        $this->hasTerminal = $hasTerminal;

        return $this;
    }

    public function getTerminalName(): ?string
    {
        return $this->terminalName;
    }

    public function setTerminalName(?string $terminalName): self
    {
        $this->terminalName = $terminalName;

        return $this;
    }

    public function getTerminalNumber(): ?int
    {
        return $this->terminalNumber;
    }

    public function setTerminalNumber(?int $terminalNumber): self
    {
        $this->terminalNumber = $terminalNumber;

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
