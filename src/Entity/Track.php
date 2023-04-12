<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TrackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrackRepository::class)]
#[ApiResource]
class Track
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'numTrack')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Airport $airport = null;

    #[ORM\ManyToMany(targetEntity: FlightSchedule::class, mappedBy: 'departureTrack')]
    private Collection $flightSchedulesDepartures;

    #[ORM\ManyToMany(targetEntity: FlightSchedule::class, mappedBy: 'arrivalTrack')]
    private Collection $flightSchedulesArrivals;

    #[ORM\ManyToOne(inversedBy: 'departureTrack')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FlightSchedule $flightScheduleDeparture = null;

    #[ORM\ManyToOne(inversedBy: 'arrivalTrack')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FlightSchedule $flightScheduleArrival = null;

    public function __construct()
    {
        $this->flightSchedulesDepartures = new ArrayCollection();
        $this->flightSchedulesArrivals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

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

    /**
     * @return Collection<int, FlightSchedule>
     */
    public function getFlightSchedulesDepartures(): Collection
    {
        return $this->flightSchedulesDepartures;
    }

    public function addFlightSchedulesDeparture(FlightSchedule $flightSchedulesDeparture): self
    {
        if (!$this->flightSchedulesDepartures->contains($flightSchedulesDeparture)) {
            $this->flightSchedulesDepartures->add($flightSchedulesDeparture);
            $flightSchedulesDeparture->addDepartureTrack($this);
        }

        return $this;
    }

    public function removeFlightSchedulesDeparture(FlightSchedule $flightSchedulesDeparture): self
    {
        if ($this->flightSchedulesDepartures->removeElement($flightSchedulesDeparture)) {
            $flightSchedulesDeparture->removeDepartureTrack($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, FlightSchedule>
     */
    public function getFlightSchedulesArrivals(): Collection
    {
        return $this->flightSchedulesArrivals;
    }

    public function addFlightSchedulesArrival(FlightSchedule $flightSchedulesArrival): self
    {
        if (!$this->flightSchedulesArrivals->contains($flightSchedulesArrival)) {
            $this->flightSchedulesArrivals->add($flightSchedulesArrival);
            $flightSchedulesArrival->addArrivalTrack($this);
        }

        return $this;
    }

    public function removeFlightSchedulesArrival(FlightSchedule $flightSchedulesArrival): self
    {
        if ($this->flightSchedulesArrivals->removeElement($flightSchedulesArrival)) {
            $flightSchedulesArrival->removeArrivalTrack($this);
        }

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
}
