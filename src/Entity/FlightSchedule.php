<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\FlightScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FlightScheduleRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['flightSchedule:item:read', 'flightSchedule:item:get']],
            security: 'is_granted("ROLE_USER")'
        ),
        new GetCollection(
            security: 'is_granted("ROLE_USER")'
        ),
        new Post(security: 'is_granted("ROLE_USER")'),
        new Patch(
            denormalizationContext: ['groups' => ['flightSchedule:items:write']],
            security: 'is_granted("ROLE_ADMIN") or (is_granted("ROLE_USER"))',
//            securityPostDenormalize: 'object.getOwner() == user',
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN") or (is_granted("ROLE_USER"))',
//            securityPostDenormalize: 'object.getOwner() == user'
        ),
    ],
    normalizationContext: [
        'groups' => ['flightSchedule:read']
    ],
    denormalizationContext: [
        'groups' => ['flightSchedule:write']
    ],
    paginationItemsPerPage: 20,
)]
class FlightSchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['flightSchedule:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['flightSchedule:read', 'flightSchedule:write', 'flightSchedule:item:read', 'flightSchedule:item:get', 'flightSchedule:items:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['flightSchedule:read', 'flightSchedule:write', 'flightSchedule:item:read', 'flightSchedule:item:get', 'flightSchedule:items:write'])]
    private ?string $idFlight = null;

    #[ORM\ManyToOne(inversedBy: 'flightSchedules')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['flightSchedule:read', 'flightSchedule:write', 'flightSchedule:item:read', 'flightSchedule:item:get', 'flightSchedule:items:write'])]
    private ?Warplane $assignedPlane = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['flightSchedule:read', 'flightSchedule:write', 'flightSchedule:item:read', 'flightSchedule:item:get', 'flightSchedule:items:write'])]
    private ?\DateTimeInterface $departureTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['flightSchedule:read', 'flightSchedule:write', 'flightSchedule:item:read', 'flightSchedule:item:get', 'flightSchedule:items:write'])]
    private ?\DateTimeInterface $arrivalTime = null;

    #[ORM\ManyToOne(inversedBy: 'flightScheduleDeparture')]
    #[Groups(['flightSchedule:read', 'flightSchedule:write', 'flightSchedule:item:read', 'flightSchedule:item:get', 'flightSchedule:items:write'])]
    private ?Track $departureTrack = null;

    #[ORM\ManyToOne(inversedBy: 'flightScheduleArrival')]
    #[Groups(['flightSchedule:read', 'flightSchedule:write', 'flightSchedule:item:read', 'flightSchedule:item:get', 'flightSchedule:items:write'])]
    private ?Track $arrivalTrack = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
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
     * @return Track|null
     */
    public function getDepartureTrack(): ?Track
    {
        return $this->departureTrack;
    }

    /**
     * @param Track|null $departureTrack
     */
    public function setDepartureTrack(?Track $departureTrack): void
    {
        $this->departureTrack = $departureTrack;
    }

    /**
     * @return Track|null
     */
    public function getArrivalTrack(): ?Track
    {
        return $this->arrivalTrack;
    }

    /**
     * @param Track|null $arrivalTrack
     */
    public function setArrivalTrack(?Track $arrivalTrack): void
    {
        $this->arrivalTrack = $arrivalTrack;
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
