<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\TrackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TrackRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            security: 'is_granted("ROLE_USER")'
        ),
        new Post(security: 'is_granted("ROLE_USER")'),
        new Patch(
            denormalizationContext: ['groups' => ['track:items:write']],
            security: 'is_granted("ROLE_ADMIN") or (is_granted("ROLE_USER"))',
//            securityPostDenormalize: 'object.getOwner() == user',
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN") or (is_granted("ROLE_USER"))',
//            securityPostDenormalize: 'object.getOwner() == user'
        ),
    ],
    normalizationContext: [
        'groups' => ['track:read']
    ],
    denormalizationContext: [
        'groups' => ['track:write']
    ],
    paginationItemsPerPage: 20,
)]
class Track
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['track:read', 'airport:collection:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['track:read', 'track:write', 'airport:Track', 'flightSchedule:Track', 'warplane:collection:read', 'flightSchedule:collection:read', 'airport:collection:read'])]
    private ?int $idTrackNumber = null;

    #[ORM\Column(length: 50)]
    #[Groups(['track:read', 'track:write', 'airport:Track', 'flightSchedule:Track', 'warplane:collection:read', 'flightSchedule:collection:read', 'airport:collection:read'])]
    private ?string $trackNameQfu = null;

    #[ORM\ManyToOne(inversedBy: 'numTrack')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['track:read', 'track:write', 'flightSchedule:collection:read'])]
    private ?Airport $airport = null;

    #[ORM\OneToMany(mappedBy: 'departureTrack', targetEntity: FlightSchedule::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['track:read'])]
    private Collection $flightScheduleDeparture;

    #[ORM\OneToMany(mappedBy: 'arrivalTrack', targetEntity: FlightSchedule::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['track:read'])]
    private Collection $flightScheduleArrival;

    #[ORM\Column(nullable: true)]
    #[Groups(['track:read', 'track:write', 'airport:Track', 'flightSchedule:Track', 'warplane:collection:read', 'flightSchedule:collection:read', 'airport:collection:read'])]
    private ?bool $hasTerminal = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['track:read', 'track:write', 'airport:Track', 'flightSchedule:Track', 'warplane:collection:read', 'flightSchedule:collection:read', 'airport:collection:read'])]
    private ?int $terminalNumber = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->flightScheduleDeparture = new ArrayCollection();
        $this->flightScheduleArrival = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }


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

    public function getTrackNameQfu(): ?string
    {
        return $this->trackNameQfu;
    }

    public function setTrackNameQfu(string $trackNameQfu): self
    {
        $this->trackNameQfu = $trackNameQfu;

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

    /**
     * @return Collection
     */
    public function getFlightScheduleDeparture(): Collection
    {
        return $this->flightScheduleDeparture;
    }

    public function addFlightScheduleDeparture(FlightSchedule $flightScheduleDeparture): self
    {
        if (!$this->flightScheduleDeparture->contains($flightScheduleDeparture)) {
            $this->flightScheduleDeparture->add($flightScheduleDeparture);
            $flightScheduleDeparture->setDepartureTrack($this);
        }

        return $this;
    }

    public function removeFlightScheduleDeparture(FlightSchedule $flightScheduleDeparture): self
    {
        if ($this->flightScheduleDeparture->removeElement($flightScheduleDeparture)) {
            // set the owning side to null (unless already changed)
            if ($flightScheduleDeparture->getDepartureTrack() === $this) {
                $flightScheduleDeparture->setDepartureTrack(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getFlightScheduleArrival(): Collection
    {
        return $this->flightScheduleArrival;
    }

    public function addFlightScheduleArrival(FlightSchedule $flightScheduleArrival): self
    {
        if (!$this->flightScheduleArrival->contains($flightScheduleArrival)) {
            $this->flightScheduleArrival->add($flightScheduleArrival);
            $flightScheduleArrival->setArrivalTrack($this);
        }

        return $this;
    }

    public function removeFlightScheduleArrival(FlightSchedule $flightScheduleArrival): self
    {
        if ($this->flightScheduleArrival->removeElement($flightScheduleArrival)) {
            // set the owning side to null (unless already changed)
            if ($flightScheduleArrival->getArrivalTrack() === $this) {
                $flightScheduleArrival->setArrivalTrack(null);
            }
        }

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
