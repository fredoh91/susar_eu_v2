<?php

namespace App\Entity;

// use Doctrine\ORM\Mapping as ORM;
// use App\Repository\SusarEURepository;

class SearchSusarEU
{
    private ?int $idSusar = null;
    private ?int $master_id = null;
    private ?int $caseid = null;
    private ?string $specificcaseid = null;
    private ?int $DLPVersion = null;
    private ?\DateTimeInterface $creationdate = null;
    private ?\DateTimeInterface $statusdate = null;
    private ?string $studytitle = null;
    private ?string $sponsorstudynumb = null;
    private ?string $num_eudract = null;
    private ?string $pays_etude = null;
    private ?string $TypeSusar = null;
    private ?string $indication = null;
    private ?string $indication_eng = null;
    private ?string $productName = null;
    private ?string $substanceName = null;
    private ?string $Commentaire = null;
    private ?\DateTimeInterface $dateEvaluation = null;
    private ?Medicaments $Medicament = null;
    private ?EffetsIndesirables $EffetsIndesirables = null;
    private ?string $effetIndesirable = null;
    private ?string $narratif = null;
    private ?string $pays_survenue = null;
    private ?\DateTimeInterface $dateAiguillage = null;
    private ?\DateTimeInterface $GatewayDate = null;
    private ?\DateTimeInterface $debutGatewayDate = null;
    private ?\DateTimeInterface $finGatewayDate = null;
    private ?\DateTimeInterface $dateImport = null;
    private ?\DateTimeInterface $debutDateImport = null;
    private ?\DateTimeInterface $finDateImport = null;
    private ?int $NbMedicSuspect = null;
    private ?string $patientAgeGroup = null;
    private ?float $patientAge = null;
    private ?string $patientAgeUnitLabel = null;
    private ?string $isCaseSerious = null;
    private ?string $seriousnessCriteria = null;
    private ?string $patientSex = null;
    // private ?Indications $IndicationsTable = null;
    private ?MedicalHistory $medicalHistories = null;
    private ?string $worldWide_id = null;
    private ?string $seriousnessCriteria_brut = null;
    private ?string $utilisateurEvaluation = null;
    private ?string $utilisateurAiguillage = null;
    private ?string $utilisateurImport = null;
    private ?\DateTimeImmutable $createdAt = null;
    private ?\DateTimeImmutable $updatedAt = null;
    private ?IntervenantSubstanceDMM $intervenantSubstanceDMMs = null;
    private ?string $IntervenantANSM = null;
    private ?string $dmmPoleChoice = null;
    private ?string $evaluateurAttribue = null;
    private ?string $evaluateurEvaluation = null;
    private ?SubstancePt $substancePts = null;
    // private ?SubstancePtEval $substancePtEvals = null;
    private ?bool $niveau1 = true;
    private ?bool $niveau2a = true;
    private ?bool $niveau2b = true;
    private ?bool $niveau2c = true;
    private ?string $casTraite = null;
    private ?string $casArchive = null;
    private ?string $assessment_outcome = null;
    private ?string $caseVersion = null;
    private ?string $casIME = null;
    private ?string $casDME = null;
    private ?string $casEurope = null;
    private ?string $type_saMS_Mono = null;

    public function getIdSusar(): ?int
    {
        return $this->idSusar;
    }
    public function setIdSusar(int $idSusar): self
    {
        $this->idSusar = $idSusar;

        return $this;
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

    /**
     * Get the value of dmmPoleChoice
     */
    public function getDmmPoleChoice()
    {
        return $this->dmmPoleChoice;
    }

    /**
     * Set the value of dmmPoleChoice
     *
     * @return  self
     */
    public function setDmmPoleChoice($dmmPoleChoice)
    {
        $this->dmmPoleChoice = $dmmPoleChoice;

        return $this;
    }

    /**
     * Get the value of evaluateurAttribue
     */
    public function getEvaluateurAttribue()
    {
        return $this->evaluateurAttribue;
    }

    /**
     * Set the value of evaluateurAttribue
     *
     * @return  self
     */
    public function setEvaluateurAttribue($evaluateurAttribue)
    {
        $this->evaluateurAttribue = $evaluateurAttribue;

        return $this;
    }

    public function getDebutDateImport(): ?\DateTimeInterface
    {
        return $this->debutDateImport;
    }

    public function setDebutDateImport(?\DateTimeInterface $debutDateImport): self
    {
        $this->debutDateImport = $debutDateImport;

        return $this;
    }

    public function getFinDateImport(): ?\DateTimeInterface
    {
        return $this->finDateImport;
    }

    public function setFinDateImport(?\DateTimeInterface $finDateImport): self
    {
        $this->finDateImport = $finDateImport;

        return $this;
    }


    /**
     * Get the value of effetIndesirable
     */
    public function getEffetIndesirable()
    {
        return $this->effetIndesirable;
    }

    /**
     * Set the value of effetIndesirable
     *
     * @return  self
     */
    public function setEffetIndesirable($effetIndesirable)
    {
        $this->effetIndesirable = $effetIndesirable;

        return $this;
    }

    /**
     * permet d'afficher un objet SearchSusarEU avec un dump()
     *
     * @return string
     */
    public function __toString()
    {
        $properties = get_object_vars($this);
        $string = '';
        foreach ($properties as $key => $value) {
            if ($value instanceof \DateTimeInterface) {
                $value = $value->format('Y-m-d H:i:s');
            }
            $string .= sprintf('%s=%s, ', $key, $value);
        }
        return sprintf('SearchSusarEU(%s)', rtrim($string, ', '));
    }

    /**
     * Get the value of niveau1
     */
    public function getNiveau1()
    {
        return $this->niveau1;
    }

    /**
     * Set the value of niveau1
     *
     * @return  self
     */
    public function setNiveau1($niveau1)
    {
        $this->niveau1 = $niveau1;

        return $this;
    }

    /**
     * Get the value of niveau2a
     */
    public function getNiveau2a()
    {
        return $this->niveau2a;
    }

    /**
     * Set the value of niveau2a
     *
     * @return  self
     */
    public function setNiveau2a($niveau2a)
    {
        $this->niveau2a = $niveau2a;

        return $this;
    }

    /**
     * Get the value of niveau2b
     */
    public function getNiveau2b()
    {
        return $this->niveau2b;
    }

    /**
     * Set the value of niveau2b
     *
     * @return  self
     */
    public function setNiveau2b($niveau2b)
    {
        $this->niveau2b = $niveau2b;

        return $this;
    }

    /**
     * Get the value of niveau2c
     */
    public function getNiveau2c()
    {
        return $this->niveau2c;
    }

    /**
     * Set the value of niveau2c
     *
     * @return  self
     */
    public function setNiveau2c($niveau2c)
    {
        $this->niveau2c = $niveau2c;

        return $this;
    }


    /**
     * Get the value of casTraite
     */
    public function getCasTraite(): ?string
    {
        return $this->casTraite;
    }

    /**
     * Set the value of casTraite
     *
     * @return  self
     */
    public function setCasTraite(?string $casTraite)
    {
        $this->casTraite = $casTraite;

        return $this;
    }

    /**
     * Get the value of casArchive
     */
    public function getCasArchive()
    {
        return $this->casArchive;
    }

    /**
     * Set the value of casArchive
     *
     * @return  self
     */
    public function setCasArchive($casArchive)
    {
        $this->casArchive = $casArchive;

        return $this;
    }

    /**
     * Get the value of assessment_outcome
     */
    public function getAssessmentOutcome(): ?string
    {
        return $this->assessment_outcome;
    }

    /**
     * Set the value of assessment_outcome
     *
     * @return  self
     */
    public function setAssessmentOutcome(?string $assessment_outcome)
    {
        $this->assessment_outcome = $assessment_outcome;

        return $this;
    }

    /**
     * Get the value of caseVersion
     */
    public function getCaseVersion(): ?string
    {
        return $this->caseVersion;
    }

    /**
     * Set the value of caseVersion
     *
     * @return  self
     */
    public function setCaseVersion(?string $caseVersion)
    {
        $this->caseVersion = $caseVersion;

        return $this;
    }

    /**
     * Get the value of casIME
     */
    public function getCasIME(): ?string
    {
        return $this->casIME;
    }

    /**
     * Set the value of casIME
     *
     * @return  self
     */
    public function setCasIME(?string $casIME)
    {
        $this->casIME = $casIME;

        return $this;
    }

    /**
     * Get the value of casDME
     */
    public function getCasDME(): ?string
    {
        return $this->casDME;
    }

    /**
     * Set the value of casDME
     *
     * @return  self
     */
    public function setCasDME(?string $casDME)
    {
        $this->casDME = $casDME;

        return $this;
    }

    /**
     * Get the value of casEurope
     */
    public function getCasEurope(): ?string
    {
        return $this->casEurope;
    }

    /**
     * Set the value of casEurope
     *
     * @return  self
     */
    public function setCasEurope(?string $casEurope)
    {
        $this->casEurope = $casEurope;

        return $this;
    }



    public function getTypeSaMSMono(): ?string
    {
        return $this->type_saMS_Mono;
    }

    public function setTypeSaMSMono(?string $type_saMS_Mono): static
    {
        $this->type_saMS_Mono = $type_saMS_Mono;

        return $this;
    }


    /**
     * Get the value of IntervenantANSM
     */
    public function getIntervenantANSM()
    {
        return $this->IntervenantANSM;
    }

    /**
     * Set the value of IntervenantANSM
     *
     * @return  self
     */
    public function setIntervenantANSM($IntervenantANSM)
    {
        $this->IntervenantANSM = $IntervenantANSM;

        return $this;
    }

    /**
     * Get the value of evaluateurEvaluation
     */
    public function getEvaluateurEvaluation()
    {
        return $this->evaluateurEvaluation;
    }

    /**
     * Set the value of evaluateurEvaluation
     *
     * @return  self
     */
    public function setEvaluateurEvaluation($evaluateurEvaluation)
    {
        $this->evaluateurEvaluation = $evaluateurEvaluation;

        return $this;
    }

    /**
     * Get the value of GatewayDate
     */
    public function getGatewayDate()
    {
        return $this->GatewayDate;
    }

    /**
     * Set the value of GatewayDate
     *
     * @return  self
     */
    public function setGatewayDate($GatewayDate)
    {
        $this->GatewayDate = $GatewayDate;

        return $this;
    }

    /**
     * Get the value of debutGatewayDate
     */
    public function getDebutGatewayDate()
    {
        return $this->debutGatewayDate;
    }

    /**
     * Set the value of debutGatewayDate
     *
     * @return  self
     */
    public function setDebutGatewayDate($debutGatewayDate)
    {
        $this->debutGatewayDate = $debutGatewayDate;

        return $this;
    }

    /**
     * Get the value of finGatewayDate
     */
    public function getFinGatewayDate()
    {
        return $this->finGatewayDate;
    }

    /**
     * Set the value of finGatewayDate
     *
     * @return  self
     */
    public function setFinGatewayDate($finGatewayDate)
    {
        $this->finGatewayDate = $finGatewayDate;

        return $this;
    }
}
