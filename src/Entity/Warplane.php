<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\WarplaneRepository;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: WarplaneRepository::class)]
#[Vich\Uploadable]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['warplane:item:read', 'warplane:item:get']],
            security: 'is_granted("ROLE_USER")'
        ),
        new GetCollection(security: 'is_granted("ROLE_USER")'),
        new Post(inputFormats: ['multipart' => ['multipart/form-data']], security: 'is_granted("ROLE_USER")'),
        new Patch(
            denormalizationContext: ['groups' => ['warplane:items:write']],
            security: 'is_granted("ROLE_ADMIN") or (is_granted("ROLE_USER"))',
//            securityPostDenormalize: 'object.getOwner() == user',
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN") or (is_granted("ROLE_USER"))',
//            securityPostDenormalize: 'object.getOwner() == user'
        ),
    ],
    normalizationContext: [
        'groups' => ['warplane:read']
    ],
    denormalizationContext: [
        'groups' => ['warplane:write']
    ],
    paginationItemsPerPage: 20
)]
#[ApiResource(
    uriTemplate: '/users/{user_id}/warplanes.{_format}',
    operations: [new GetCollection()],
    uriVariables: [
        'user_id' => new Link(
            fromProperty: 'warplanes',
            fromClass: User::class
        )
    ],
    normalizationContext: [
        'groups' => ['warplane:collection:read']
    ],
    security: 'is_granted("ROLE_USER")'
)]
class Warplane
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['warplane:read', 'warplane:collection:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['warplane:read', 'warplane:collection:read', 'warplane:write', 'user:read', 'warplane:item:read', 'warplane:item:get', 'warplane:items:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['warplane:read', 'warplane:collection:read', 'warplane:write', 'user:read', 'warplane:item:read', 'warplane:item:get', 'warplane:items:write'])]
    private ?string $armament = null;

    #[ORM\OneToMany(mappedBy: 'assignedPlane', targetEntity: FlightSchedule::class)]
    #[Groups(['warplane:read', 'warplane:collection:read', 'warplane:write', 'user:read', 'warplane:item:read', 'warplane:item:get'])]
    private Collection $flightSchedules;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['warplane:read', 'warplane:collection:read', 'warplane:item:get'])]
    #[ApiProperty(types: ['https://schema.org/contentUrl'])]
    public ?string $contentUrl = null;

    #[Vich\UploadableField(mapping: "warplane_image", fileNameProperty: "filePath")]
    #[Groups(['warplane:write', 'warplane:item:read'])]
    public ?File $file = null;

    #[ORM\Column(nullable: true)]
    public ?string $filePath = null;

    #[ORM\ManyToOne(inversedBy: 'warplanes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['warplane:read', 'warplane:collection:read', 'warplane:write', 'warplane:items:write'])]
    private ?User $owner = null;

    public function __construct()
    {
        $this->flightSchedules = new ArrayCollection();
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

    /**
     * A human-readable representation of when this treasure was plundered.
     */
    #[Groups(['warplane:read', 'user:read'])]
    public function getCreatedAtAgo(): string
    {
        return Carbon::instance($this->createdAt)->diffForHumans();
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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContentUrl(): ?string
    {
        return $this->contentUrl;
    }

    /**
     * @param string|null $contentUrl
     */
    public function setContentUrl(?string $contentUrl): void
    {
        $this->contentUrl = $contentUrl;
    }
}
