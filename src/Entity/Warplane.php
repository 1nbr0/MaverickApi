<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\WarplaneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WarplaneRepository::class)]
#[ApiResource]
class Warplane
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $armament = null;

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
}
