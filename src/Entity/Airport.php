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
use App\Repository\AirportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AirportRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            security: 'is_granted("ROLE_USER")'
        ),
        new Get(
            normalizationContext: [
                'groups' => ['airport:collection:read']
            ],
            security: 'is_granted("ROLE_ADMIN") or (is_granted("ROLE_USER"))'
        ),
        new Post(security: 'is_granted("ROLE_USER")'),
        new Patch(
            denormalizationContext: ['groups' => ['airport:items:write']],
            security: 'is_granted("ROLE_ADMIN") or (is_granted("ROLE_USER"))',
//            securityPostDenormalize: 'object.getOwner() == user',
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN") or (is_granted("ROLE_USER"))',
//            securityPostDenormalize: 'object.getOwner() == user'
        ),
    ],
    normalizationContext: [
        'groups' => ['airport:read', 'airport:Track']
    ],
    denormalizationContext: [
        'groups' => ['airport:write']
    ],
    paginationItemsPerPage: 20,
)]
class Airport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['airport:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['airport:read', 'airport:write', 'flightSchedule:collection:read', 'flightSchedule:item:read'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['airport:read', 'airport:write', 'flightSchedule:collection:read', 'flightSchedule:item:read'])]
    private ?int $nbrTrack = null;

    #[ORM\Column(length: 20)]
    #[Groups(['airport:read', 'airport:write', 'flightSchedule:collection:read', 'flightSchedule:item:read'])]
    private ?string $latitude = null;

    #[ORM\Column(length: 20)]
    #[Groups(['airport:read', 'airport:write', 'flightSchedule:collection:read', 'flightSchedule:item:read'])]
    private ?string $longitude = null;

    #[ORM\OneToMany(mappedBy: 'airport', targetEntity: Track::class)]
    #[Groups(['airport:read', 'airport:Track', 'airport:collection:read'])]
    private Collection $numTrack;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->numTrack = new ArrayCollection();
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
