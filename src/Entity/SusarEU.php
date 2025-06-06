<?php

namespace App\Entity;

use App\Repository\SusarEURepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

#[ORM\Entity(repositoryClass: SusarEURepository::class)]
#[Index(name: "idx_ev_safety_report_identifier", columns: ["ev_safety_report_identifier"])]
class SusarEU
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $EV_SafetyReportIdentifier = null;
    
    #[ORM\Column(nullable: true)]
    private ?int $master_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $caseid = null;

    #[ORM\Column(length: 16,nullable: true)]
    private ?string $specificcaseid = null;

    #[ORM\Column(nullable: true)]
    private ?int $DLPVersion = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $creationdate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $statusdate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $studytitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sponsorstudynumb = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $num_eudract = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pays_etude = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $TypeSusar = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $indication = null;

    // #[ORM\ManyToOne(inversedBy: 'susars')]
    // private ?IntervenantsANSM $intervenantANSM = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $indication_eng = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $productName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $substanceName = null;

    // #[ORM\ManyToOne(inversedBy: 'susars')]
    // private ?MesureAction $MesureAction = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Commentaire = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateEvaluation = null;

    #[ORM\OneToMany(mappedBy: 'susar', targetEntity: Medicaments::class)]
    private Collection $Medicament;

    #[ORM\OneToMany(mappedBy: 'susar', targetEntity: EffetsIndesirables::class)]
    private Collection $EffetsIndesirables;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $narratif = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pays_survenue = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateAiguillage = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateImport = null;

    #[ORM\Column(nullable: true)]
    private ?int $NbMedicSuspect = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $patientAgeGroup = null;

    #[ORM\Column(nullable: true)]
    private ?float $patientAge = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $patientAgeUnitLabel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $isCaseSerious = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $seriousnessCriteria = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $patientSex = null;

    #[ORM\OneToMany(mappedBy: 'susar', targetEntity: Indications::class)]
    private Collection $IndicationsTable;

    #[ORM\OneToMany(mappedBy: 'susar', targetEntity: MedicalHistory::class)]
    private Collection $medicalHistories;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $worldWide_id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $seriousnessCriteria_brut = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $utilisateurEvaluation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $utilisateurAiguillage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $utilisateurImport = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: IntervenantSubstanceDMM::class, mappedBy: 'susarEUs')]
    private Collection $intervenantSubstanceDMMs;

    #[ORM\ManyToMany(targetEntity: SubstancePt::class, mappedBy: 'susarEUs')]
    private Collection $substancePts;

    #[ORM\ManyToMany(targetEntity: SubstancePtEval::class, mappedBy: 'susarEUs')]
    private Collection $substancePtEvals;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $priorisation = null;

    #[ORM\Column(nullable: true)]
    private ?bool $CasIME = null;

    #[ORM\Column(nullable: true)]
    private ?bool $CasDME = null;

    #[ORM\Column(nullable: true)]
    private ?bool $CasEurope = null;

    #[ORM\Column(nullable: true)]
    private ?bool $casSusarEuV1 = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateRepriseSusarEuV1 = null;

    #[ORM\Column(nullable: true)]
    private ?int $idCTLL = null;

    // #[ORM\Column(length: 1000, nullable: true)]
    // private ?string $StudyRegistrationN = null;

    #[ORM\OneToOne(inversedBy: 'susarEU', cascade: ['persist'])]
    private ?ImportCtll $ImportCtll = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $ReceiveDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $ReceiptDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $GatewayDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $InitialsHeightWeight = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $BirthDate = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $PrimarySourceQualification = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $ParentChild = null;

    #[ORM\Column(nullable: true)]
    private ?int $narratifNbCaractere = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ICSR_formLink = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $E2B_link = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $CompleteNarrativeLink = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sender = null;

    // #[ORM\ManyToMany(targetEntity: IntervenantSubstanceDMM::class, inversedBy: 'susarEUs')]
    // private Collection $IntervenantSubstanceDMM;

    // #[ORM\ManyToMany(targetEntity: SubstancePt::class, mappedBy: 'susarEUs')]
    // private Collection $substancePts;

    // #[ORM\ManyToMany(targetEntity: SubstancePtEval::class, mappedBy: 'susarEUs')]
    // private Collection $substancePtEvals;

    public function __construct()
    {
        $this->Medicament = new ArrayCollection();
        $this->EffetsIndesirables = new ArrayCollection();
        $this->IndicationsTable = new ArrayCollection();
        $this->medicalHistories = new ArrayCollection();
        // $this->IntervenantSubstanceDMM = new ArrayCollection();
        $this->substancePts = new ArrayCollection();
        $this->substancePtEvals = new ArrayCollection();
        $this->intervenantSubstanceDMMs = new ArrayCollection();
    }

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

    public function getCreationdate(): ?\DateTimeInterface
    {
        return $this->creationdate;
    }

    public function setCreationdate(?\DateTimeInterface $creationdate): self
    {
        $this->creationdate = $creationdate;

        return $this;
    }

    public function getStatusdate(): ?\DateTimeInterface
    {
        return $this->statusdate;
    }

    public function setStatusdate(?\DateTimeInterface $statusdate): self
    {
        $this->statusdate = $statusdate;

        return $this;
    }

    public function getStudytitle(): ?string
    {
        return $this->studytitle;
    }

    public function setStudytitle(?string $studytitle): self
    {
        $this->studytitle = $studytitle;

        return $this;
    }

    public function getSponsorstudynumb(): ?string
    {
        return $this->sponsorstudynumb;
    }

    public function setSponsorstudynumb(?string $sponsorstudynumb): self
    {
        $this->sponsorstudynumb = $sponsorstudynumb;

        return $this;
    }

    public function getNumEudract(): ?string
    {
        return $this->num_eudract;
    }

    public function setNumEudract(?string $num_eudract): self
    {
        $this->num_eudract = $num_eudract;

        return $this;
    }

    public function getPaysEtude(): ?string
    {
        return $this->pays_etude;
    }

    public function setPaysEtude(?string $pays_etude): self
    {
        $this->pays_etude = $pays_etude;

        return $this;
    }

    public function getTypeSusar(): ?string
    {
        return $this->TypeSusar;
    }

    public function setTypeSusar(?string $TypeSusar): self
    {
        $this->TypeSusar = $TypeSusar;

        return $this;
    }

    public function getIndication(): ?string
    {
        return $this->indication;
    }

    public function setIndication(?string $indication): self
    {
        $this->indication = $indication;

        return $this;
    }

    // public function getIntervenantANSM(): ?intervenantsANSM
    // {
    //     return $this->intervenantANSM;
    // }

    // public function setIntervenantANSM(?intervenantsANSM $intervenantANSM): self
    // {
    //     $this->intervenantANSM = $intervenantANSM;

    //     return $this;
    // }

    // public function __toString(){
    //     return $this->IntervenantsANSM; // Remplacer champ par une propriété "string" de l'entité
    // }

    public function getIndicationEng(): ?string
    {
        return $this->indication_eng;
    }

    public function setIndicationEng(?string $indication_eng): self
    {
        $this->indication_eng = $indication_eng;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(?string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getSubstanceName(): ?string
    {
        return $this->substanceName;
    }

    public function setSubstanceName(?string $substanceName): self
    {
        $this->substanceName = $substanceName;

        return $this;
    }

    // public function getMesureAction(): ?MesureAction
    // {
    //     return $this->MesureAction;
    // }

    // public function setMesureAction(?MesureAction $MesureAction): self
    // {
    //     $this->MesureAction = $MesureAction;

    //     return $this;
    // }

    public function getCommentaire(): ?string
    {
        return $this->Commentaire;
    }

    public function setCommentaire(?string $Commentaire): self
    {
        $this->Commentaire = $Commentaire;

        return $this;
    }

    public function getDateEvaluation(): ?\DateTimeInterface
    {
        return $this->dateEvaluation;
    }

    public function setDateEvaluation(?\DateTimeInterface $dateEvaluation): self
    {
        $this->dateEvaluation = $dateEvaluation;

        return $this;
    }

    /**
     * @return Collection<int, Medicaments>
     */
    public function getMedicament(): Collection
    {
        return $this->Medicament;
    }

    public function addMedicament(Medicaments $medicament): self
    {
        if (!$this->Medicament->contains($medicament)) {
            $this->Medicament->add($medicament);
            $medicament->setSusar($this);
        }

        return $this;
    }

    public function removeMedicament(Medicaments $medicament): self
    {
        if ($this->Medicament->removeElement($medicament)) {
            // set the owning side to null (unless already changed)
            if ($medicament->getSusar() === $this) {
                $medicament->setSusar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EffetsIndesirables>
     */
    public function getEffetsIndesirables(): Collection
    {
        return $this->EffetsIndesirables;
    }

    public function addEffetsIndesirable(EffetsIndesirables $effetsIndesirable): self
    {
        if (!$this->EffetsIndesirables->contains($effetsIndesirable)) {
            $this->EffetsIndesirables->add($effetsIndesirable);
            $effetsIndesirable->setSusar($this);
        }

        return $this;
    }

    public function removeEffetsIndesirable(EffetsIndesirables $effetsIndesirable): self
    {
        if ($this->EffetsIndesirables->removeElement($effetsIndesirable)) {
            // set the owning side to null (unless already changed)
            if ($effetsIndesirable->getSusar() === $this) {
                $effetsIndesirable->setSusar(null);
            }
        }

        return $this;
    }

    public function getNarratif(): ?string
    {
        return $this->narratif;
    }

    public function setNarratif(?string $narratif): self
    {
        $this->narratif = $narratif;

        return $this;
    }

    public function getPaysSurvenue(): ?string
    {
        return $this->pays_survenue;
    }

    public function setPaysSurvenue(?string $pays_survenue): self
    {
        $this->pays_survenue = $pays_survenue;

        return $this;
    }

    public function getDateAiguillage(): ?\DateTimeInterface
    {
        return $this->dateAiguillage;
    }

    public function setDateAiguillage(?\DateTimeInterface $dateAiguillage): self
    {
        $this->dateAiguillage = $dateAiguillage;

        return $this;
    }

    public function getDateImport(): ?\DateTimeInterface
    {
        return $this->dateImport;
    }

    public function setDateImport(?\DateTimeInterface $DateImport): self
    {
        $this->dateImport = $DateImport;

        return $this;
    }

    public function getNbMedicSuspect(): ?int
    {
        return $this->NbMedicSuspect;
    }

    public function setNbMedicSuspect(?int $NbMedicSuspect): self
    {
        $this->NbMedicSuspect = $NbMedicSuspect;

        return $this;
    }

    public function getPatientAgeGroup(): ?string
    {
        return $this->patientAgeGroup;
    }

    public function setPatientAgeGroup(?string $patientAgeGroup): self
    {
        $this->patientAgeGroup = $patientAgeGroup;

        return $this;
    }

    public function getPatientAge(): ?float
    {
        return $this->patientAge;
    }

    public function setPatientAge(?float $patientAge): self
    {
        $this->patientAge = $patientAge;

        return $this;
    }

    public function getPatientAgeUnitLabel(): ?string
    {
        return $this->patientAgeUnitLabel;
    }

    public function setPatientAgeUnitLabel(?string $patientAgeUnitLabel): self
    {
        $this->patientAgeUnitLabel = $patientAgeUnitLabel;

        return $this;
    }

    public function getIsCaseSerious(): ?string
    {
        return $this->isCaseSerious;
    }

    public function setIsCaseSerious(?string $isCaseSerious): self
    {
        $this->isCaseSerious = $isCaseSerious;

        return $this;
    }

    public function getSeriousnessCriteria(): ?string
    {
        return $this->seriousnessCriteria;
    }

    public function setSeriousnessCriteria(?string $seriousnessCriteria): self
    {
        $this->seriousnessCriteria = $seriousnessCriteria;

        return $this;
    }

    public function getPatientSex(): ?string
    {
        return $this->patientSex;
    }

    public function setPatientSex(?string $patientSex): self
    {
        $this->patientSex = $patientSex;

        return $this;
    }

    /**
     * @return Collection<int, Indications>
     */
    public function getIndicationsTable(): Collection
    {
        return $this->IndicationsTable;
    }

    public function addIndicationsTable(Indications $IndicationsTable): self
    {
        if (!$this->IndicationsTable->contains($IndicationsTable)) {
            $this->IndicationsTable->add($IndicationsTable);
            $IndicationsTable->setSusar($this);
        }

        return $this;
    }

    public function removeIndicationsTable(Indications $IndicationsTable): self
    {
        if ($this->IndicationsTable->removeElement($IndicationsTable)) {
            // set the owning side to null (unless already changed)
            if ($IndicationsTable->getSusar() === $this) {
                $IndicationsTable->setSusar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MedicalHistory>
     */
    public function getMedicalHistories(): Collection
    {
        return $this->medicalHistories;
    }

    public function addMedicalHistory(MedicalHistory $medicalHistory): self
    {
        if (!$this->medicalHistories->contains($medicalHistory)) {
            $this->medicalHistories->add($medicalHistory);
            $medicalHistory->setSusar($this);
        }

        return $this;
    }

    public function removeMedicalHistory(MedicalHistory $medicalHistory): self
    {
        if ($this->medicalHistories->removeElement($medicalHistory)) {
            // set the owning side to null (unless already changed)
            if ($medicalHistory->getSusar() === $this) {
                $medicalHistory->setSusar(null);
            }
        }

        return $this;
    }

    public function getWorldWideId(): ?string
    {
        return $this->worldWide_id;
    }

    public function setWorldWideId(?string $worldWide_id): self
    {
        $this->worldWide_id = $worldWide_id;

        return $this;
    }

    public function getSeriousnessCriteriaBrut(): ?string
    {
        return $this->seriousnessCriteria_brut;
    }

    public function setSeriousnessCriteriaBrut(?string $seriousnessCriteria_brut): self
    {
        $this->seriousnessCriteria_brut = $seriousnessCriteria_brut;

        return $this;
    }

    public function getUtilisateurEvaluation(): ?string
    {
        return $this->utilisateurEvaluation;
    }

    public function setUtilisateurEvaluation(?string $utilisateurEvaluation): self
    {
        $this->utilisateurEvaluation = $utilisateurEvaluation;

        return $this;
    }

    public function getUtilisateurAiguillage(): ?string
    {
        return $this->utilisateurAiguillage;
    }

    public function setUtilisateurAiguillage(?string $utilisateurAiguillage): self
    {
        $this->utilisateurAiguillage = $utilisateurAiguillage;

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

    // /**
    //  * @return Collection<int, IntervenantSubstanceDMM>
    //  */
    // public function getIntervenantSubstanceDMM(): Collection
    // {
    //     return $this->IntervenantSubstanceDMM;
    // }

    // public function addIntervenantSubstanceDMM(IntervenantSubstanceDMM $intervenantSubstanceDMM): static
    // {
    //     if (!$this->IntervenantSubstanceDMM->contains($intervenantSubstanceDMM)) {
    //         $this->IntervenantSubstanceDMM->add($intervenantSubstanceDMM);
    //     }

    //     return $this;
    // }

    // public function removeIntervenantSubstanceDMM(IntervenantSubstanceDMM $intervenantSubstanceDMM): static
    // {
    //     $this->IntervenantSubstanceDMM->removeElement($intervenantSubstanceDMM);

    //     return $this;
    // }

    // /**
    //  * @return Collection<int, SubstancePt>
    //  */
    // public function getSubstancePts(): Collection
    // {
    //     return $this->substancePts;
    // }

    // public function addSubstancePt(SubstancePt $substancePt): static
    // {
    //     if (!$this->substancePts->contains($substancePt)) {
    //         $this->substancePts->add($substancePt);
    //         $substancePt->addSusarEUs($this);
    //     }

    //     return $this;
    // }

    // public function removeSubstancePt(SubstancePt $substancePt): static
    // {
    //     if ($this->substancePts->removeElement($substancePt)) {
    //         $substancePt->removeSusarEUs($this);
    //     }

    //     return $this;
    // }

    // /**
    //  * @return Collection<int, SubstancePtEval>
    //  */
    // public function getSubstancePtEvals(): Collection
    // {
    //     return $this->substancePtEvals;
    // }

    // public function addSubstancePtEval(SubstancePtEval $substancePtEval): static
    // {
    //     if (!$this->substancePtEvals->contains($substancePtEval)) {
    //         $this->substancePtEvals->add($substancePtEval);
    //         $substancePtEval->addSusarEUs($this);
    //     }

    //     return $this;
    // }

    // public function removeSubstancePtEval(SubstancePtEval $substancePtEval): static
    // {
    //     if ($this->substancePtEvals->removeElement($substancePtEval)) {
    //         $substancePtEval->removeSusarEUs($this);
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, IntervenantSubstanceDMM>
     */
    public function getIntervenantSubstanceDMMs(): Collection
    {
        return $this->intervenantSubstanceDMMs;
    }

    public function addIntervenantSubstanceDMM(IntervenantSubstanceDMM $intervenantSubstanceDMM): static
    {
        if (!$this->intervenantSubstanceDMMs->contains($intervenantSubstanceDMM)) {
            $this->intervenantSubstanceDMMs->add($intervenantSubstanceDMM);
            $intervenantSubstanceDMM->addSusarEUs($this);
        }

        return $this;
    }

    public function removeIntervenantSubstanceDMM(IntervenantSubstanceDMM $intervenantSubstanceDMM): static
    {
        if ($this->intervenantSubstanceDMMs->removeElement($intervenantSubstanceDMM)) {
            $intervenantSubstanceDMM->removeSusarEUs($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, SubstancePt>
     */
    public function getSubstancePts(): Collection
    {
        return $this->substancePts;
    }

    public function addSubstancePt(SubstancePt $substancePt): static
    {
        if (!$this->substancePts->contains($substancePt)) {
            $this->substancePts->add($substancePt);
            $substancePt->addSusarEUs($this);
        }

        return $this;
    }

    public function removeSubstancePt(SubstancePt $substancePt): static
    {
        if ($this->substancePts->removeElement($substancePt)) {
            $substancePt->removeSusarEUs($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, SubstancePtEval>
     */
    public function getSubstancePtEvals(): Collection
    {
        return $this->substancePtEvals;
    }

    public function addSubstancePtEval(SubstancePtEval $substancePtEval): static
    {
        if (!$this->substancePtEvals->contains($substancePtEval)) {
            $this->substancePtEvals->add($substancePtEval);
            $substancePtEval->addSusarEUs($this);
        }

        return $this;
    }

    public function removeSubstancePtEval(SubstancePtEval $substancePtEval): static
    {
        if ($this->substancePtEvals->removeElement($substancePtEval)) {
            $substancePtEval->removeSusarEUs($this);
        }

        return $this;
    }

    public function getPriorisation(): ?string
    {
        return $this->priorisation;
    }

    public function setPriorisation(?string $priorisation): static
    {
        $this->priorisation = $priorisation;

        return $this;
    }

    public function isCasIME(): ?bool
    {
        return $this->CasIME;
    }

    public function setCasIME(?bool $CasIME): static
    {
        $this->CasIME = $CasIME;

        return $this;
    }

    public function isCasDME(): ?bool
    {
        return $this->CasDME;
    }

    public function setCasDME(?bool $CasDME): static
    {
        $this->CasDME = $CasDME;

        return $this;
    }

    public function isCasEurope(): ?bool
    {
        return $this->CasEurope;
    }

    public function setCasEurope(?bool $CasEurope): static
    {
        $this->CasEurope = $CasEurope;

        return $this;
    }

    public function isCasSusarEuV1(): ?bool
    {
        return $this->casSusarEuV1;
    }

    public function setCasSusarEuV1(?bool $casSusarEuV1): static
    {
        $this->casSusarEuV1 = $casSusarEuV1;

        return $this;
    }

    public function getDateRepriseSusarEuV1(): ?\DateTimeImmutable
    {
        return $this->dateRepriseSusarEuV1;
    }

    public function setDateRepriseSusarEuV1(?\DateTimeImmutable $dateRepriseSusarEuV1): static
    {
        $this->dateRepriseSusarEuV1 = $dateRepriseSusarEuV1;

        return $this;
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

    public function getEVSafetyReportIdentifier(): ?string
    {
        return $this->EV_SafetyReportIdentifier;
    }

    public function setEVSafetyReportIdentifier(string $EV_SafetyReportIdentifier): static
    {
        $this->EV_SafetyReportIdentifier = $EV_SafetyReportIdentifier;

        return $this;
    }

    // public function getStudyRegistrationN(): ?string
    // {
    //     return $this->StudyRegistrationN;
    // }

    // public function setStudyRegistrationN(?string $StudyRegistrationN): static
    // {
    //     $this->StudyRegistrationN = $StudyRegistrationN;

    //     return $this;
    // }

    public function getImportCtll(): ?ImportCtll
    {
        return $this->ImportCtll;
    }

    public function setImportCtll(?ImportCtll $ImportCtll): static
    {
        $this->ImportCtll = $ImportCtll;

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

    public function getInitialsHeightWeight(): ?string
    {
        return $this->InitialsHeightWeight;
    }

    public function setInitialsHeightWeight(?string $InitialsHeightWeight): static
    {
        $this->InitialsHeightWeight = $InitialsHeightWeight;

        return $this;
    }

    public function getBirthDate(): ?string
    {
        return $this->BirthDate;
    }

    public function setBirthDate(?string $BirthDate): static
    {
        $this->BirthDate = $BirthDate;

        return $this;
    }

    public function getPrimarySourceQualification(): ?string
    {
        return $this->PrimarySourceQualification;
    }

    public function setPrimarySourceQualification(?string $PrimarySourceQualification): static
    {
        $this->PrimarySourceQualification = $PrimarySourceQualification;

        return $this;
    }

    public function getParentChild(): ?string
    {
        return $this->ParentChild;
    }

    public function setParentChild(?string $ParentChild): static
    {
        $this->ParentChild = $ParentChild;

        return $this;
    }

    public function getNarratifNbCaractere(): ?int
    {
        return $this->narratifNbCaractere;
    }

    public function setNarratifNbCaractere(?int $narratifNbCaractere): static
    {
        $this->narratifNbCaractere = $narratifNbCaractere;

        return $this;
    }

    public function getICSRFormLink(): ?string
    {
        return $this->ICSR_formLink;
    }

    public function setICSRFormLink(?string $ICSR_formLink): static
    {
        $this->ICSR_formLink = $ICSR_formLink;

        return $this;
    }

    public function getE2BLink(): ?string
    {
        return $this->E2B_link;
    }

    public function setE2BLink(?string $E2B_link): static
    {
        $this->E2B_link = $E2B_link;

        return $this;
    }

    public function getCompleteNarrativeLink(): ?string
    {
        return $this->CompleteNarrativeLink;
    }

    public function setCompleteNarrativeLink(?string $CompleteNarrativeLink): static
    {
        $this->CompleteNarrativeLink = $CompleteNarrativeLink;

        return $this;
    }

    public function getSender(): ?string
    {
        return $this->sender;
    }

    public function setSender(?string $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

}
