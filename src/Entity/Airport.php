<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AirportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AirportRepository::class)]
#[ApiResource]
class Airport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $nbrTrack = null;

    #[ORM\Column(length: 20)]
    private ?string $latitude = null;

    #[ORM\Column(length: 20)]
    private ?string $longitude = null;

    #[ORM\OneToMany(mappedBy: 'airport', targetEntity: Track::class)]
    private Collection $numTrack;

    public function __construct()
    {
        $this->numTrack = new ArrayCollection();
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

    public function getNbrTrack(): ?int
    {
        return $this->nbrTrack;
    }

    public function setNbrTrack(int $nbrTrack): self
    {
        $this->nbrTrack = $nbrTrack;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, Track>
     */
    public function getNumTrack(): Collection
    {
        return $this->numTrack;
    }

    public function addNumTrack(Track $numTrack): self
    {
        if (!$this->numTrack->contains($numTrack)) {
            $this->numTrack->add($numTrack);
            $numTrack->setAirport($this);
        }

        return $this;
    }

    public function removeNumTrack(Track $numTrack): self
    {
        if ($this->numTrack->removeElement($numTrack)) {
            // set the owning side to null (unless already changed)
            if ($numTrack->getAirport() === $this) {
                $numTrack->setAirport(null);
            }
        }

        return $this;
    }
}
