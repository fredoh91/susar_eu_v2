<?php

namespace App\Entity;

use App\Repository\LienEvaluationBnpvRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LienEvaluationBnpvRepository::class)]
class LienEvaluationBnpv
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $idCTLL = null;

    #[ORM\Column(nullable: true)]
    private ?int $idProduit_PT_Eval = null;

    #[ORM\Column(nullable: true)]
    private ?int $idProduit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $DCI_EU = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $susar_eu_active_substance_high_level = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $substance_susar_eu_v1 = null;

    #[ORM\Column(nullable: true)]
    private ?int $idProduit_PT = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Changes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $AssessmentOutcome = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Comments = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Date = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateCrea = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $UtilisateurCrea = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateModif = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $UtilisateurModif = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCTLL(): ?int
    {
        return $this->idCTLL;
    }

    public function setIdCTLL(?int $idCTLL): static
    {
        $this->idCTLL = $idCTLL;

        return $this;
    }

    public function getIdProduitPTEval(): ?int
    {
        return $this->idProduit_PT_Eval;
    }

    public function setIdProduitPTEval(?int $idProduit_PT_Eval): static
    {
        $this->idProduit_PT_Eval = $idProduit_PT_Eval;

        return $this;
    }

    public function getIdProduit(): ?int
    {
        return $this->idProduit;
    }

    public function setIdProduit(?int $idProduit): static
    {
        $this->idProduit = $idProduit;

        return $this;
    }

    public function getDCIEU(): ?string
    {
        return $this->DCI_EU;
    }

    public function setDCIEU(?string $DCI_EU): static
    {
        $this->DCI_EU = $DCI_EU;

        return $this;
    }

    public function getSusarEuActiveSubstanceHighLevel(): ?string
    {
        return $this->susar_eu_active_substance_high_level;
    }

    public function setSusarEuActiveSubstanceHighLevel(?string $susar_eu_active_substance_high_level): static
    {
        $this->susar_eu_active_substance_high_level = $susar_eu_active_substance_high_level;

        return $this;
    }

    public function getSubstanceSusarEuV1(): ?string
    {
        return $this->substance_susar_eu_v1;
    }

    public function setSubstanceSusarEuV1(?string $substance_susar_eu_v1): static
    {
        $this->substance_susar_eu_v1 = $substance_susar_eu_v1;

        return $this;
    }

    public function getIdProduitPT(): ?int
    {
        return $this->idProduit_PT;
    }

    public function setIdProduitPT(?int $idProduit_PT): static
    {
        $this->idProduit_PT = $idProduit_PT;

        return $this;
    }

    public function getChanges(): ?string
    {
        return $this->Changes;
    }

    public function setChanges(?string $Changes): static
    {
        $this->Changes = $Changes;

        return $this;
    }

    public function getAssessmentOutcome(): ?string
    {
        return $this->AssessmentOutcome;
    }

    public function setAssessmentOutcome(?string $AssessmentOutcome): static
    {
        $this->AssessmentOutcome = $AssessmentOutcome;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->Comments;
    }

    public function setComments(?string $Comments): static
    {
        $this->Comments = $Comments;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(?\DateTimeInterface $Date): static
    {
        $this->Date = $Date;

        return $this;
    }

    public function getDateCrea(): ?\DateTimeImmutable
    {
        return $this->dateCrea;
    }

    public function setDateCrea(?\DateTimeImmutable $dateCrea): static
    {
        $this->dateCrea = $dateCrea;

        return $this;
    }

    public function getUtilisateurCrea(): ?string
    {
        return $this->UtilisateurCrea;
    }

    public function setUtilisateurCrea(?string $UtilisateurCrea): static
    {
        $this->UtilisateurCrea = $UtilisateurCrea;

        return $this;
    }

    public function getDateModif(): ?\DateTimeImmutable
    {
        return $this->dateModif;
    }

    public function setDateModif(?\DateTimeImmutable $dateModif): static
    {
        $this->dateModif = $dateModif;

        return $this;
    }

    public function getUtilisateurModif(): ?string
    {
        return $this->UtilisateurModif;
    }

    public function setUtilisateurModif(?string $UtilisateurModif): static
    {
        $this->UtilisateurModif = $UtilisateurModif;

        return $this;
    }
}
