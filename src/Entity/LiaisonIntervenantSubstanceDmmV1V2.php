<?php

namespace App\Entity;

use App\Repository\LiaisonIntervenantSubstanceDmmV1V2Repository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LiaisonIntervenantSubstanceDmmV1V2Repository::class)]
class LiaisonIntervenantSubstanceDmmV1V2
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idIntervenantSubstanceDMM = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $DMM = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $PoleLong = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $PoleCourt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Evaluateur = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $ActiveSubstanceHighLevel = null;

    #[ORM\Column(nullable: true)]
    private ?bool $Inactif = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $TypeSaMSMono = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $UpdatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $AssociationDeSubstances = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userCreate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userModif = null;

    #[ORM\Column(nullable: true)]
    private ?int $IdInterSubDMMSusarEUV1 = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $DCI_EU_v1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Type_saMS_Mono_v1 = null;

    #[ORM\Column(nullable: true)]
    private ?bool $inactif_v1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $evaluateur_v1 = null;

    #[ORM\Column(nullable: true)]
    private ?int $NbLigne_v1 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdIntervenantSubstanceDMM(): ?int
    {
        return $this->idIntervenantSubstanceDMM;
    }

    public function setIdIntervenantSubstanceDMM(int $idIntervenantSubstanceDMM): static
    {
        $this->idIntervenantSubstanceDMM = $idIntervenantSubstanceDMM;

        return $this;
    }

    public function getDMM(): ?string
    {
        return $this->DMM;
    }

    public function setDMM(?string $DMM): static
    {
        $this->DMM = $DMM;

        return $this;
    }

    public function getPoleLong(): ?string
    {
        return $this->PoleLong;
    }

    public function setPoleLong(?string $PoleLong): static
    {
        $this->PoleLong = $PoleLong;

        return $this;
    }

    public function getPoleCourt(): ?string
    {
        return $this->PoleCourt;
    }

    public function setPoleCourt(?string $PoleCourt): static
    {
        $this->PoleCourt = $PoleCourt;

        return $this;
    }

    public function getEvaluateur(): ?string
    {
        return $this->Evaluateur;
    }

    public function setEvaluateur(?string $Evaluateur): static
    {
        $this->Evaluateur = $Evaluateur;

        return $this;
    }

    public function getActiveSubstanceHighLevel(): ?string
    {
        return $this->ActiveSubstanceHighLevel;
    }

    public function setActiveSubstanceHighLevel(?string $ActiveSubstanceHighLevel): static
    {
        $this->ActiveSubstanceHighLevel = $ActiveSubstanceHighLevel;

        return $this;
    }

    public function isInactif(): ?bool
    {
        return $this->Inactif;
    }

    public function setInactif(?bool $Inactif): static
    {
        $this->Inactif = $Inactif;

        return $this;
    }

    public function getTypeSaMSMono(): ?string
    {
        return $this->TypeSaMSMono;
    }

    public function setTypeSaMSMono(?string $TypeSaMSMono): static
    {
        $this->TypeSaMSMono = $TypeSaMSMono;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $CreatedAt): static
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->UpdatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $UpdatedAt): static
    {
        $this->UpdatedAt = $UpdatedAt;

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

    public function getUserCreate(): ?string
    {
        return $this->userCreate;
    }

    public function setUserCreate(?string $userCreate): static
    {
        $this->userCreate = $userCreate;

        return $this;
    }

    public function getUserModif(): ?string
    {
        return $this->userModif;
    }

    public function setUserModif(?string $userModif): static
    {
        $this->userModif = $userModif;

        return $this;
    }

    public function getIdInterSubDMMSusarEUV1(): ?int
    {
        return $this->IdInterSubDMMSusarEUV1;
    }

    public function setIdInterSubDMMSusarEUV1(?int $IdInterSubDMMSusarEUV1): static
    {
        $this->IdInterSubDMMSusarEUV1 = $IdInterSubDMMSusarEUV1;

        return $this;
    }

    public function getDCIEUV1(): ?string
    {
        return $this->DCI_EU_v1;
    }

    public function setDCIEUV1(?string $DCI_EU_v1): static
    {
        $this->DCI_EU_v1 = $DCI_EU_v1;

        return $this;
    }

    public function getTypeSaMSMonoV1(): ?string
    {
        return $this->Type_saMS_Mono_v1;
    }

    public function setTypeSaMSMonoV1(?string $Type_saMS_Mono_v1): static
    {
        $this->Type_saMS_Mono_v1 = $Type_saMS_Mono_v1;

        return $this;
    }

    public function isInactifV1(): ?bool
    {
        return $this->inactif_v1;
    }

    public function setInactifV1(?bool $inactif_v1): static
    {
        $this->inactif_v1 = $inactif_v1;

        return $this;
    }

    public function getEvaluateurV1(): ?string
    {
        return $this->evaluateur_v1;
    }

    public function setEvaluateurV1(?string $evaluateur_v1): static
    {
        $this->evaluateur_v1 = $evaluateur_v1;

        return $this;
    }

    public function getNbLigneV1(): ?int
    {
        return $this->NbLigne_v1;
    }

    public function setNbLigneV1(?int $NbLigne_v1): static
    {
        $this->NbLigne_v1 = $NbLigne_v1;

        return $this;
    }
}
