<?php

namespace App\Entity;

use App\Repository\PaysEuropeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaysEuropeRepository::class)]
class PaysEurope
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $LibPays = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $CodePays = null;

    #[ORM\Column]
    private ?bool $inactif = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibPays(): ?string
    {
        return $this->LibPays;
    }

    public function setLibPays(string $LibPays): static
    {
        $this->LibPays = $LibPays;

        return $this;
    }

    public function getCodePays(): ?string
    {
        return $this->CodePays;
    }

    public function setCodePays(?string $CodePays): static
    {
        $this->CodePays = $CodePays;

        return $this;
    }

    public function isInactif(): ?bool
    {
        return $this->inactif;
    }

    public function setInactif(bool $inactif): static
    {
        $this->inactif = $inactif;

        return $this;
    }
}
