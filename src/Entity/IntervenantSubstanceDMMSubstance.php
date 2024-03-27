<?php

namespace App\Entity;

use App\Repository\IntervenantSubstanceDMMSubstanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IntervenantSubstanceDMMSubstanceRepository::class)]
class IntervenantSubstanceDMMSubstance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 1000)]
    private ?string $active_substance_high_level = null;

    #[ORM\Column(nullable: true)]
    private ?bool $inactif = null;

    #[ORM\ManyToOne(inversedBy: 'intervenantSubstanceDMMSubstances')]
    private ?IntervenantSubstanceDMM $IntervenantSubstanceDMM = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActiveSubstanceHighLevel(): ?string
    {
        return $this->active_substance_high_level;
    }

    public function setActiveSubstanceHighLevel(string $active_substance_high_level): static
    {
        $this->active_substance_high_level = $active_substance_high_level;

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

    public function getIntervenantSubstanceDMM(): ?IntervenantSubstanceDMM
    {
        return $this->IntervenantSubstanceDMM;
    }

    public function setIntervenantSubstanceDMM(?IntervenantSubstanceDMM $IntervenantSubstanceDMM): static
    {
        $this->IntervenantSubstanceDMM = $IntervenantSubstanceDMM;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
