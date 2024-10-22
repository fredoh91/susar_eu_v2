<?php

namespace App\Entity;

use App\Repository\LienCtllBnpvRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LienCtllBnpvRepository::class)]
class LienCtllBnpv
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idCTLL = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $EV_SafetyReportIdentifier = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $CaseReportNumber = null;

    #[ORM\Column(nullable: true)]
    private ?int $CaseVersion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $StudyRegistrationNumber = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $ReceiveDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $ReceiptDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $GatewayDate = null;

    #[ORM\Column(nullable: true)]
    private ?int $master_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $caseid = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $specificcaseid = null;

    #[ORM\Column(nullable: true)]
    private ?int $dlpversion = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $DateImport = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $UtilisateurImport = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCTLL(): ?int
    {
        return $this->idCTLL;
    }

    public function setIdCTLL(int $idCTLL): static
    {
        $this->idCTLL = $idCTLL;

        return $this;
    }

    public function getEVSafetyReportIdentifier(): ?string
    {
        return $this->EV_SafetyReportIdentifier;
    }

    public function setEVSafetyReportIdentifier(?string $EV_SafetyReportIdentifier): static
    {
        $this->EV_SafetyReportIdentifier = $EV_SafetyReportIdentifier;

        return $this;
    }

    public function getCaseReportNumber(): ?string
    {
        return $this->CaseReportNumber;
    }

    public function setCaseReportNumber(?string $CaseReportNumber): static
    {
        $this->CaseReportNumber = $CaseReportNumber;

        return $this;
    }

    public function getCaseVersion(): ?int
    {
        return $this->CaseVersion;
    }

    public function setCaseVersion(?int $CaseVersion): static
    {
        $this->CaseVersion = $CaseVersion;

        return $this;
    }

    public function getStudyRegistrationNumber(): ?string
    {
        return $this->StudyRegistrationNumber;
    }

    public function setStudyRegistrationNumber(?string $StudyRegistrationNumber): static
    {
        $this->StudyRegistrationNumber = $StudyRegistrationNumber;

        return $this;
    }

    public function getReceiveDate(): ?\DateTimeInterface
    {
        return $this->ReceiveDate;
    }

    public function setReceiveDate(?\DateTimeInterface $ReceiveDate): static
    {
        $this->ReceiveDate = $ReceiveDate;

        return $this;
    }

    public function getReceiptDate(): ?\DateTimeInterface
    {
        return $this->ReceiptDate;
    }

    public function setReceiptDate(?\DateTimeInterface $ReceiptDate): static
    {
        $this->ReceiptDate = $ReceiptDate;

        return $this;
    }

    public function getGatewayDate(): ?\DateTimeInterface
    {
        return $this->GatewayDate;
    }

    public function setGatewayDate(?\DateTimeInterface $GatewayDate): static
    {
        $this->GatewayDate = $GatewayDate;

        return $this;
    }

    public function getMasterId(): ?int
    {
        return $this->master_id;
    }

    public function setMasterId(?int $master_id): static
    {
        $this->master_id = $master_id;

        return $this;
    }

    public function getCaseid(): ?int
    {
        return $this->caseid;
    }

    public function setCaseid(?int $caseid): static
    {
        $this->caseid = $caseid;

        return $this;
    }

    public function getSpecificcaseid(): ?string
    {
        return $this->specificcaseid;
    }

    public function setSpecificcaseid(?string $specificcaseid): static
    {
        $this->specificcaseid = $specificcaseid;

        return $this;
    }

    public function getDlpversion(): ?int
    {
        return $this->dlpversion;
    }

    public function setDlpversion(?int $dlpversion): static
    {
        $this->dlpversion = $dlpversion;

        return $this;
    }

    public function getDateImport(): ?\DateTimeImmutable
    {
        return $this->DateImport;
    }

    public function setDateImport(?\DateTimeImmutable $DateImport): static
    {
        $this->DateImport = $DateImport;

        return $this;
    }

    public function getUtilisateurImport(): ?string
    {
        return $this->UtilisateurImport;
    }

    public function setUtilisateurImport(?string $UtilisateurImport): static
    {
        $this->UtilisateurImport = $UtilisateurImport;

        return $this;
    }
}
