<?php

namespace App\Entity;

use App\Repository\ActiveSubstanceGroupingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActiveSubstanceGroupingRepository::class)]
class ActiveSubstanceGrouping
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 1000)]
    private ?string $ActiveSubstanceHighLevel = null;

    #[ORM\Column(length: 1000)]
    private ?string $ActiveSubstanceLowLevel = null;

    #[ORM\Column(nullable: true)]
    private ?bool $inactif = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $DateFichier = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $utilisateurImport = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActiveSubstanceHighLevel(): ?string
    {
        return $this->ActiveSubstanceHighLevel;
    }

    public function setActiveSubstanceHighLevel(string $ActiveSubstanceHighLevel): static
    {
        $this->ActiveSubstanceHighLevel = $ActiveSubstanceHighLevel;

        return $this;
    }

    public function getActiveSubstanceLowLevel(): ?string
    {
        return $this->ActiveSubstanceLowLevel;
    }

    public function setActiveSubstanceLowLevel(string $ActiveSubstanceLowLevel): static
    {
        $this->ActiveSubstanceLowLevel = $ActiveSubstanceLowLevel;

        return $this;
    }

    public function isInactif(): ?bool
    {
        return $this->inactif;
    }

    public function setInactif(?bool $inactif): static
    {
        $this->inactif = $inactif;

        return $this;
    }

    public function getDateFichier(): ?\DateTimeInterface
    {
        return $this->DateFichier;
    }

    public function setDateFichier(\DateTimeInterface $DateFichier): static
    {
        $this->DateFichier = $DateFichier;

        return $this;
    }

    public function getUtilisateurImport(): ?string
    {
        return $this->utilisateurImport;
    }

    public function setUtilisateurImport(?string $utilisateurImport): static
    {
        $this->utilisateurImport = $utilisateurImport;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
