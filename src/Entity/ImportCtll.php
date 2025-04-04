<?php

namespace App\Entity;

use App\Repository\ImportCtllRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: ImportCtllRepository::class)]
class ImportCtll
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "bigint")]
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
    private ?ImportCtllFicExcel $ImportCtllFicExcel = null;

    #[ORM\Column(length: 255)]
    private ?string $Sender = null;

    #[ORM\Column(length: 255)]
    private ?string $ReportType = null;

    #[ORM\Column(length: 255)]
    private ?string $EV_DocumentType = null;

    #[ORM\Column(length: 255)]
    private ?string $Country = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $ReceiveDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $ReceiptDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $GatewayDate = null;

    #[ORM\Column(length: 255)]
    private ?string $InitialsHeightWeight = null;

    #[ORM\Column(length: 255)]
    private ?string $Age = null;

    #[ORM\Column(length: 255)]
    private ?string $BirthDate = null;

    #[ORM\Column(length: 255)]
    private ?string $Sex = null;

    #[ORM\Column(length: 255)]
    private ?string $AgeGroup = null;

    #[ORM\Column(length: 255)]
    private ?string $PrimarySourceQualification = null;

    #[ORM\Column(length: 255)]
    private ?string $Serious = null;

    #[ORM\Column(length: 255)]
    private ?string $SeriousnessDeath = null;

    #[ORM\Column(length: 255)]
    private ?string $SeriousnessLifethreatening = null;

    #[ORM\Column(length: 255)]
    private ?string $SeriousnessHospitalisation = null;

    #[ORM\Column(length: 255)]
    private ?string $SeriousnessDisabling = null;

    #[ORM\Column(length: 255)]
    private ?string $SeriousnessCongenitalAnomaly = null;

    #[ORM\Column(length: 255)]
    private ?string $SeriousnessOther = null;

    #[ORM\Column(length: 255)]
    private ?string $ParentChild = null;

    #[ORM\Column(length: 255)]
    private ?string $LiteratureReference = null;

    #[ORM\Column(type: 'integer')]
    private ?int $NumberOfLiteratureReferenceDocuments = null;

    #[ORM\Column(type: 'integer')]
    private ?int $NumberOfDocumentsHeldBySender = null;

    #[ORM\Column(type: 'text')]
    private ?string $RecodedDrugList = null;

    #[ORM\Column(type: 'integer')]
    private ?int $NumberOfSuspectInteractingDrugs = null;

    #[ORM\Column(type: 'text')]
    private ?string $SuspectInteractingEnhancedReportedDrugList = null;

    #[ORM\Column(type: 'text')]
    private ?string $ConcomitantNotAdministeredEnhancedReportedDrugList = null;

    #[ORM\Column(type: 'text')]
    private ?string $IndicationsPTOfTheDrugOfInterestAsReportedInTheICSR = null;

    #[ORM\Column(type: 'text')]
    private ?string $PositiveRechallengeForSuspectInteractingDrugs = null;

    #[ORM\Column(type: 'text')]
    private ?string $ReactionListPT = null;

    #[ORM\Column(type: 'text')]
    private ?string $StructuredMedicalHistory = null;

    #[ORM\Column(length: 255)]
    private ?string $NarrativePresent = null;

    #[ORM\Column(type: 'text')]
    private ?string $NarrativeReportersCommentsAndSendersComments = null;

    #[ORM\Column(length: 1000)]
    private ?string $ICSRForm = null;

    #[ORM\Column(length: 255)]
    private ?string $E2B = null;

    #[ORM\Column(type: "bigint")]
    private ?int $SafetyReportID = null;

    #[ORM\Column(type: 'bigint')]
    private ?int $SelectICSR = null;

    #[ORM\Column(type: 'text')]
    private ?string $CompleteNarrativeReportersCommentsAndSendersComments = null;

    #[ORM\Column(type: 'integer')]
    private ?int $CaseVersion = null;

    #[ORM\OneToOne(mappedBy: 'ImportCtll', cascade: ['persist', 'remove'])]
    private ?SusarEU $susarEU = null;

    #[ORM\Column(nullable: true)]
    private ?bool $SusarAttribue = null;

    #[ORM\Column(nullable: true)]
    private ?bool $SusarDejaExistant = null;

    // Getters and Setters
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

    public function getImportCtllFicExcel(): ?ImportCtllFicExcel
    {
        return $this->ImportCtllFicExcel;
    }

    public function setImportCtllFicExcel(?ImportCtllFicExcel $ImportCtllFicExcel): static
    {
        $this->ImportCtllFicExcel = $ImportCtllFicExcel;

        return $this;
    }

    public function getSender(): ?string
    {
        return $this->Sender;
    }

    public function setSender(string $Sender): static
    {
        $this->Sender = $Sender;

        return $this;
    }

    public function getReportType(): ?string
    {
        return $this->ReportType;
    }

    public function setReportType(string $ReportType): static
    {
        $this->ReportType = $ReportType;

        return $this;
    }

    public function getEVDocumentType(): ?string
    {
        return $this->EV_DocumentType;
    }

    public function setEVDocumentType(string $EV_DocumentType): static
    {
        $this->EV_DocumentType = $EV_DocumentType;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->Country;
    }

    public function setCountry(string $Country): static
    {
        $this->Country = $Country;

        return $this;
    }

    public function getReceiveDate(): ?\DateTimeInterface
    {
        return $this->ReceiveDate;
    }

    public function setReceiveDate(\DateTimeInterface $ReceiveDate): static
    {
        $this->ReceiveDate = $ReceiveDate;

        return $this;
    }

    public function getReceiptDate(): ?\DateTimeInterface
    {
        return $this->ReceiptDate;
    }

    public function setReceiptDate(\DateTimeInterface $ReceiptDate): static
    {
        $this->ReceiptDate = $ReceiptDate;

        return $this;
    }

    public function getGatewayDate(): ?\DateTimeInterface
    {
        return $this->GatewayDate;
    }

    public function setGatewayDate(\DateTimeInterface $GatewayDate): static
    {
        $this->GatewayDate = $GatewayDate;

        return $this;
    }

    public function getInitialsHeightWeight(): ?string
    {
        return $this->InitialsHeightWeight;
    }

    public function setInitialsHeightWeight(string $InitialsHeightWeight): static
    {
        $this->InitialsHeightWeight = $InitialsHeightWeight;

        return $this;
    }

    public function getAge(): ?string
    {
        return $this->Age;
    }

    public function setAge(string $Age): static
    {
        $this->Age = $Age;

        return $this;
    }

    public function getBirthDate(): ?string
    {
        return $this->BirthDate;
    }

    public function setBirthDate(string $BirthDate): static
    {
        $this->BirthDate = $BirthDate;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->Sex;
    }

    public function setSex(string $Sex): static
    {
        $this->Sex = $Sex;

        return $this;
    }

    public function getAgeGroup(): ?string
    {
        return $this->AgeGroup;
    }

    public function setAgeGroup(string $AgeGroup): static
    {
        $this->AgeGroup = $AgeGroup;

        return $this;
    }

    public function getPrimarySourceQualification(): ?string
    {
        return $this->PrimarySourceQualification;
    }

    public function setPrimarySourceQualification(string $PrimarySourceQualification): static
    {
        $this->PrimarySourceQualification = $PrimarySourceQualification;

        return $this;
    }

    public function getSerious(): ?string
    {
        return $this->Serious;
    }

    public function setSerious(string $Serious): static
    {
        $this->Serious = $Serious;

        return $this;
    }

    public function getSeriousnessDeath(): ?string
    {
        return $this->SeriousnessDeath;
    }

    public function setSeriousnessDeath(string $SeriousnessDeath): static
    {
        $this->SeriousnessDeath = $SeriousnessDeath;

        return $this;
    }

    public function getSeriousnessLifethreatening(): ?string
    {
        return $this->SeriousnessLifethreatening;
    }

    public function setSeriousnessLifethreatening(string $SeriousnessLifethreatening): static
    {
        $this->SeriousnessLifethreatening = $SeriousnessLifethreatening;

        return $this;
    }

    public function getSeriousnessHospitalisation(): ?string
    {
        return $this->SeriousnessHospitalisation;
    }

    public function setSeriousnessHospitalisation(string $SeriousnessHospitalisation): static
    {
        $this->SeriousnessHospitalisation = $SeriousnessHospitalisation;

        return $this;
    }

    public function getSeriousnessDisabling(): ?string
    {
        return $this->SeriousnessDisabling;
    }

    public function setSeriousnessDisabling(string $SeriousnessDisabling): static
    {
        $this->SeriousnessDisabling = $SeriousnessDisabling;

        return $this;
    }

    public function getSeriousnessCongenitalAnomaly(): ?string
    {
        return $this->SeriousnessCongenitalAnomaly;
    }

    public function setSeriousnessCongenitalAnomaly(string $SeriousnessCongenitalAnomaly): static
    {
        $this->SeriousnessCongenitalAnomaly = $SeriousnessCongenitalAnomaly;

        return $this;
    }

    public function getSeriousnessOther(): ?string
    {
        return $this->SeriousnessOther;
    }

    public function setSeriousnessOther(string $SeriousnessOther): static
    {
        $this->SeriousnessOther = $SeriousnessOther;

        return $this;
    }

    public function getParentChild(): ?string
    {
        return $this->ParentChild;
    }

    public function setParentChild(string $ParentChild): static
    {
        $this->ParentChild = $ParentChild;

        return $this;
    }

    public function getLiteratureReference(): ?string
    {
        return $this->LiteratureReference;
    }

    public function setLiteratureReference(string $LiteratureReference): static
    {
        $this->LiteratureReference = $LiteratureReference;

        return $this;
    }

    public function getNumberOfLiteratureReferenceDocuments(): ?int
    {
        return $this->NumberOfLiteratureReferenceDocuments;
    }

    public function setNumberOfLiteratureReferenceDocuments(int $NumberOfLiteratureReferenceDocuments): static
    {
        $this->NumberOfLiteratureReferenceDocuments = $NumberOfLiteratureReferenceDocuments;

        return $this;
    }

    public function getNumberOfDocumentsHeldBySender(): ?int
    {
        return $this->NumberOfDocumentsHeldBySender;
    }

    public function setNumberOfDocumentsHeldBySender(int $NumberOfDocumentsHeldBySender): static
    {
        $this->NumberOfDocumentsHeldBySender = $NumberOfDocumentsHeldBySender;

        return $this;
    }

    public function getRecodedDrugList(): ?string
    {
        return $this->RecodedDrugList;
    }

    public function setRecodedDrugList(string $RecodedDrugList): static
    {
        $this->RecodedDrugList = $RecodedDrugList;

        return $this;
    }

    public function getNumberOfSuspectInteractingDrugs(): ?int
    {
        return $this->NumberOfSuspectInteractingDrugs;
    }

    public function setNumberOfSuspectInteractingDrugs(int $NumberOfSuspectInteractingDrugs): static
    {
        $this->NumberOfSuspectInteractingDrugs = $NumberOfSuspectInteractingDrugs;

        return $this;
    }

    public function getSuspectInteractingEnhancedReportedDrugList(): ?string
    {
        return $this->SuspectInteractingEnhancedReportedDrugList;
    }

    public function setSuspectInteractingEnhancedReportedDrugList(string $SuspectInteractingEnhancedReportedDrugList): static
    {
        $this->SuspectInteractingEnhancedReportedDrugList = $SuspectInteractingEnhancedReportedDrugList;

        return $this;
    }

    public function getConcomitantNotAdministeredEnhancedReportedDrugList(): ?string
    {
        return $this->ConcomitantNotAdministeredEnhancedReportedDrugList;
    }

    public function setConcomitantNotAdministeredEnhancedReportedDrugList(string $ConcomitantNotAdministeredEnhancedReportedDrugList): static
    {
        $this->ConcomitantNotAdministeredEnhancedReportedDrugList = $ConcomitantNotAdministeredEnhancedReportedDrugList;

        return $this;
    }

    public function getIndicationsPTOfTheDrugOfInterestAsReportedInTheICSR(): ?string
    {
        return $this->IndicationsPTOfTheDrugOfInterestAsReportedInTheICSR;
    }

    public function setIndicationsPTOfTheDrugOfInterestAsReportedInTheICSR(string $IndicationsPTOfTheDrugOfInterestAsReportedInTheICSR): static
    {
        $this->IndicationsPTOfTheDrugOfInterestAsReportedInTheICSR = $IndicationsPTOfTheDrugOfInterestAsReportedInTheICSR;

        return $this;
    }

    public function getPositiveRechallengeForSuspectInteractingDrugs(): ?string
    {
        return $this->PositiveRechallengeForSuspectInteractingDrugs;
    }

    public function setPositiveRechallengeForSuspectInteractingDrugs(string $PositiveRechallengeForSuspectInteractingDrugs): static
    {
        $this->PositiveRechallengeForSuspectInteractingDrugs = $PositiveRechallengeForSuspectInteractingDrugs;

        return $this;
    }

    public function getReactionListPT(): ?string
    {
        return $this->ReactionListPT;
    }

    public function setReactionListPT(string $ReactionListPT): static
    {
        $this->ReactionListPT = $ReactionListPT;

        return $this;
    }

    public function getStructuredMedicalHistory(): ?string
    {
        return $this->StructuredMedicalHistory;
    }

    public function setStructuredMedicalHistory(string $StructuredMedicalHistory): static
    {
        $this->StructuredMedicalHistory = $StructuredMedicalHistory;

        return $this;
    }

    public function getNarrativePresent(): ?string
    {
        return $this->NarrativePresent;
    }

    public function setNarrativePresent(string $NarrativePresent): static
    {
        $this->NarrativePresent = $NarrativePresent;

        return $this;
    }

    public function getNarrativeReportersCommentsAndSendersComments(): ?string
    {
        return $this->NarrativeReportersCommentsAndSendersComments;
    }

    public function setNarrativeReportersCommentsAndSendersComments(string $NarrativeReportersCommentsAndSendersComments): static
    {
        $this->NarrativeReportersCommentsAndSendersComments = $NarrativeReportersCommentsAndSendersComments;

        return $this;
    }

    public function getICSRForm(): ?string
    {
        return $this->ICSRForm;
    }

    public function setICSRForm(string $ICSRForm): static
    {
        $this->ICSRForm = $ICSRForm;

        return $this;
    }

    public function getE2B(): ?string
    {
        return $this->E2B;
    }

    public function setE2B(string $E2B): static
    {
        $this->E2B = $E2B;

        return $this;
    }

    public function getSafetyReportID(): ?int
    {
        return $this->SafetyReportID;
    }

    public function setSafetyReportID(int $SafetyReportID): static
    {
        $this->SafetyReportID = $SafetyReportID;

        return $this;
    }

    public function getSelectICSR(): ?int
    {
        return $this->SelectICSR;
    }

    public function setSelectICSR(int $SelectICSR): static
    {
        $this->SelectICSR = $SelectICSR;

        return $this;
    }

    public function getCompleteNarrativeReportersCommentsAndSendersComments(): ?string
    {
        return $this->CompleteNarrativeReportersCommentsAndSendersComments;
    }

    public function setCompleteNarrativeReportersCommentsAndSendersComments(string $CompleteNarrativeReportersCommentsAndSendersComments): static
    {
        $this->CompleteNarrativeReportersCommentsAndSendersComments = $CompleteNarrativeReportersCommentsAndSendersComments;

        return $this;
    }

    public function getCaseVersion(): ?int
    {
        return $this->CaseVersion;
    }

    public function setCaseVersion(int $CaseVersion): static
    {
        $this->CaseVersion = $CaseVersion;

        return $this;
    }

    public function getSusarEU(): ?SusarEU
    {
        return $this->susarEU;
    }

    public function setSusarEU(?SusarEU $susarEU): static
    {
        // unset the owning side of the relation if necessary
        if ($susarEU === null && $this->susarEU !== null) {
            $this->susarEU->setImportCtll(null);
        }

        // set the owning side of the relation if necessary
        if ($susarEU !== null && $susarEU->getImportCtll() !== $this) {
            $susarEU->setImportCtll($this);
        }

        $this->susarEU = $susarEU;

        return $this;
    }

    public function isSusarAttribue(): ?bool
    {
        return $this->SusarAttribue;
    }

    public function setSusarAttribue(?bool $SusarAttribue): static
    {
        $this->SusarAttribue = $SusarAttribue;

        return $this;
    }

    public function isSusarDejaExistant(): ?bool
    {
        return $this->SusarDejaExistant;
    }

    public function setSusarDejaExistant(?bool $SusarDejaExistant): static
    {
        $this->SusarDejaExistant = $SusarDejaExistant;

        return $this;
    }
}
