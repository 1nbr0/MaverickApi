<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AirportRepository;
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

    #[ORM\Column(length: 20)]
    private ?string $numTrack = null;

    #[ORM\Column]
    private ?int $nbrTrack = null;

    #[ORM\Column(length: 20)]
    private ?string $latitude = null;

    #[ORM\Column(length: 20)]
    private ?string $longitude = null;

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

    public function getNumTrack(): ?string
    {
        return $this->numTrack;
    }

    public function setNumTrack(string $numTrack): self
    {
        $this->numTrack = $numTrack;

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
}
