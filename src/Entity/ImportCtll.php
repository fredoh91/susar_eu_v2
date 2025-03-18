<?php

namespace App\Entity;

use App\Repository\ImportCtllRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImportCtllRepository::class)]
class ImportCtll
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $SafetyReportKey = null;

    #[ORM\Column(length: 1000)]
    private ?string $StudyRegistrationN = null;

    #[ORM\Column(length: 255)]
    private ?string $SponsorStudyNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $EV_SafetyReportIdentifier = null;

    #[ORM\Column(length: 1000)]
    private ?string $CaseReportNumber = null;

    #[ORM\ManyToOne(inversedBy: 'importCtlls')]
    private ?ImportCttlFicExcel $ImportCttlFicExcel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSafetyReportKey(): ?int
    {
        return $this->SafetyReportKey;
    }

    public function setSafetyReportKey(int $SafetyReportKey): static
    {
        $this->SafetyReportKey = $SafetyReportKey;

        return $this;
    }

    public function getStudyRegistrationN(): ?string
    {
        return $this->StudyRegistrationN;
    }

    public function setStudyRegistrationN(string $StudyRegistrationN): static
    {
        $this->StudyRegistrationN = $StudyRegistrationN;

        return $this;
    }

    public function getSponsorStudyNumber(): ?string
    {
        return $this->SponsorStudyNumber;
    }

    public function setSponsorStudyNumber(string $SponsorStudyNumber): static
    {
        $this->SponsorStudyNumber = $SponsorStudyNumber;

        return $this;
    }

    public function getEVSafetyReportIdentifier(): ?string
    {
        return $this->EV_SafetyReportIdentifier;
    }

    public function setEVSafetyReportIdentifier(string $EV_SafetyReportIdentifier): static
    {
        $this->EV_SafetyReportIdentifier = $EV_SafetyReportIdentifier;

        return $this;
    }

    public function getCaseReportNumber(): ?string
    {
        return $this->CaseReportNumber;
    }

    public function setCaseReportNumber(string $CaseReportNumber): static
    {
        $this->CaseReportNumber = $CaseReportNumber;

        return $this;
    }

    public function getImportCttlFicExcel(): ?ImportCttlFicExcel
    {
        return $this->ImportCttlFicExcel;
    }

    public function setImportCttlFicExcel(?ImportCttlFicExcel $ImportCttlFicExcel): static
    {
        $this->ImportCttlFicExcel = $ImportCttlFicExcel;

        return $this;
    }
}
