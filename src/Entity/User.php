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
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            security: 'is_granted("ROLE_USER")'
        ),
        new GetCollection(
            security: 'is_granted("ROLE_USER")'
        ),
        new Patch(
            security: 'is_granted("ROLE_USER")'
        ),
        new Delete(
            security: 'is_granted("ROLE_USER")'
        ),
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']],
    paginationItemsPerPage: 20,
)]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cet email')]
#[UniqueEntity(fields: ['username'], message: 'Il existe déjà un compte avec ce nom d\'utilisateur')]
#[ApiResource(
    uriTemplate: '/warplanes/{warplane_id}/owner.{_format}',
    operations: [new Get()],
    uriVariables: [
        'warplane_id' => new Link(
            fromProperty: 'owner',
            fromClass: Warplane::class,
        )
    ],
    normalizationContext: [
        'groups' => ['user:read']
    ],
    security: 'is_granted("ROLE_USER")'
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['flightSchedule:Plane'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user:read', 'user:write', 'flightSchedule:Plane'])]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['user:write', 'user:read'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['user:write'])]
    private ?string $password = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['user:read', 'user:write', 'warplane:item:get', 'flightSchedule:Plane'])]
    #[Assert\NotBlank]
    private ?string $username = null;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Warplane::class)]
    #[Groups(['user:read'])]
    private Collection $warplanes;

    #[ORM\OneToMany(mappedBy: 'ownerOfFlightSchedules', targetEntity: FlightSchedule::class)]
    private Collection $flightSchedules;

    public function __construct()
    {
        $this->warplanes = new ArrayCollection();
        $this->flightSchedules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, Warplane>
     */
    public function getWarplanes(): Collection
    {
        return $this->warplanes;
    }

    public function addWarplane(Warplane $warplane): self
    {
        if (!$this->warplanes->contains($warplane)) {
            $this->warplanes->add($warplane);
            $warplane->setOwner($this);
        }

        return $this;
    }

    public function removeWarplane(Warplane $warplane): self
    {
        if ($this->warplanes->removeElement($warplane)) {
            // set the owning side to null (unless already changed)
            if ($warplane->getOwner() === $this) {
                $warplane->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FlightSchedule>
     */
    public function getFlightSchedules(): Collection
    {
        return $this->flightSchedules;
    }

    public function addFlightSchedule(FlightSchedule $flightSchedule): static
    {
        if (!$this->flightSchedules->contains($flightSchedule)) {
            $this->flightSchedules->add($flightSchedule);
            $flightSchedule->setOwnerOfFlightSchedules($this);
        }

        return $this;
    }

    public function removeFlightSchedule(FlightSchedule $flightSchedule): static
    {
        if ($this->flightSchedules->removeElement($flightSchedule)) {
            // set the owning side to null (unless already changed)
            if ($flightSchedule->getOwnerOfFlightSchedules() === $this) {
                $flightSchedule->setOwnerOfFlightSchedules(null);
            }
        }

        return $this;
    }
}
