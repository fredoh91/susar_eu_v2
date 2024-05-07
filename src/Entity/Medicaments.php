<?php

namespace App\Entity;

use App\Repository\MedicamentsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedicamentsRepository::class)]
class Medicaments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $master_id = null;

    #[ORM\Column]
    private ?int $caseid = null;

    #[ORM\Column(length: 255)]
    private ?string $specificcaseid = null;

    #[ORM\Column]
    private ?int $DLPVersion = null;

    #[ORM\Column(length: 255)]
    private ?string $productcharacterization = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $productname = null;

    #[ORM\Column(nullable: true)]
    private ?int $NBBlock = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $substancename = null;

    #[ORM\ManyToOne(inversedBy: 'Medicament')]
    private ?SusarEU $susar = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbblock2 = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $active_substance_high_level = null;

    #[ORM\ManyToOne(inversedBy: 'medicaments')]
    private ?IntervenantSubstanceDMM $IntervenantSubstanceDMM = null;

    #[ORM\Column(nullable: true)]
    private ?bool $AssociationDeSubstances = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getMasterId(): ?int
    {
        return $this->master_id;
    }

    public function setMasterId(int $master_id): self
    {
        $this->master_id = $master_id;

        return $this;
    }

    public function getCaseid(): ?int
    {
        return $this->caseid;
    }

    public function setCaseid(int $caseid): self
    {
        $this->caseid = $caseid;

        return $this;
    }

    public function getSpecificcaseid(): ?string
    {
        return $this->specificcaseid;
    }

    public function setSpecificcaseid(string $specificcaseid): self
    {
        $this->specificcaseid = $specificcaseid;

        return $this;
    }

    public function getDLPVersion(): ?int
    {
        return $this->DLPVersion;
    }

    public function setDLPVersion(int $DLPVersion): self
    {
        $this->DLPVersion = $DLPVersion;

        return $this;
    }

    public function getProductcharacterization(): ?string
    {
        return $this->productcharacterization;
    }

    public function setProductcharacterization(string $productcharacterization): self
    {
        $this->productcharacterization = $productcharacterization;

        return $this;
    }

    public function getProductname(): ?string
    {
        return $this->productname;
    }

    public function setProductname(?string $productname): self
    {
        $this->productname = $productname;

        return $this;
    }

    public function getNBBlock(): ?int
    {
        return $this->NBBlock;
    }

    public function setNBBlock(?int $NBBlock): self
    {
        $this->NBBlock = $NBBlock;

        return $this;
    }

    public function getSubstancename(): ?string
    {
        return $this->substancename;
    }

    public function setSubstancename(?string $substancename): self
    {
        $this->substancename = $substancename;

        return $this;
    }

    public function getSusar(): ?SusarEU
    {
        return $this->susar;
    }

    public function setSusar(?SusarEU $susar): self
    {
        $this->susar = $susar;

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

    public function getNbblock2(): ?int
    {
        return $this->nbblock2;
    }

    public function setNbblock2(?int $nbblock2): static
    {
        $this->nbblock2 = $nbblock2;

        return $this;
    }

    public function getActiveSubstanceHighLevel(): ?string
    {
        return $this->active_substance_high_level;
    }

    public function setActiveSubstanceHighLevel(?string $active_substance_high_level): static
    {
        $this->active_substance_high_level = $active_substance_high_level;

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

    public function isAssociationDeSubstances(): ?bool
    {
        return $this->AssociationDeSubstances;
    }

    public function setAssociationDeSubstances(?bool $AssociationDeSubstances): static
    {
        $this->AssociationDeSubstances = $AssociationDeSubstances;

        return $this;
    }

}
