<?php

namespace App\Service;

use App\Entity\SusarEU;
use App\Entity\Indications;
use App\Entity\Medicaments;
use App\Service\Priorisation;
use App\Entity\MedicalHistory;
use App\Repository\DMERepository;
use App\Repository\IMERepository;
use App\Entity\EffetsIndesirables;
use App\Service\ParsingMedicaments;
use App\Repository\SusarEURepository;
use App\Repository\ImportCtllRepository;
use App\Repository\MeddraMdHierarchyRepository;
use App\Repository\PaysEuropeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ImportVersSusarEu
{
    // private int $numberOfInsertedRows;
    private int $idImportCtllFicExcel;
    private ImportCtllRepository $importCtllRepository;
    private SusarEURepository $susarEURepository;
    private ParsingMedicaments $parsingMedicaments;
    private Priorisation $priorisation;
    private PaysEuropeRepository $paysEuropeRepository;
    private DMERepository $dmeRepository;
    private IMERepository $imeRepository;
    private MeddraMdHierarchyRepository $meddraMdHierarchyRepository;
    private int $nbOfInsertedSusar = 0;
    private int $nbOfInsertedMedic = 0;
    private int $nbOfInsertedEffInd = 0;
    private int $nbOfInsertedMedHist = 0;
    private int $nbOfInsertedIndic = 0;

    public function __construct(
        ImportCtllRepository $importCtllRepository,
        SusarEURepository $susarEURepository,
        ParsingMedicaments $parsingMedicaments,
        Priorisation $priorisation,
        PaysEuropeRepository $paysEuropeRepository,
        DMERepository $dmeRepository,
        IMERepository $imeRepository,
        MeddraMdHierarchyRepository $meddraMdHierarchyRepository,
    ) {
        $this->importCtllRepository = $importCtllRepository;
        $this->susarEURepository = $susarEURepository;
        $this->parsingMedicaments = $parsingMedicaments;
        $this->priorisation = $priorisation;
        $this->paysEuropeRepository = $paysEuropeRepository;
        $this->dmeRepository = $dmeRepository;
        $this->imeRepository = $imeRepository;
        $this->meddraMdHierarchyRepository = $meddraMdHierarchyRepository;
    }

    public function importExcelVersTbSusarEu(int $idImportCtllFicExcel, EntityManagerInterface $em, AuthenticationUtils $authenticationUtils)
    {
        $importCtll = $this->importCtllRepository->findByIdImport($idImportCtllFicExcel);

        if ($importCtll) {

            foreach ($importCtll as $importCtll) {
                $EVSafetyReportIdentifier = $importCtll->getEVSafetyReportIdentifier();
                if (!$this->susarEURepository->existeEV_SafetyReportIdentifier($EVSafetyReportIdentifier)) {
                    $dateImport = new \DateTimeImmutable();
                    $susarEU = new SusarEU();

                    $casEurope = $this->paysEuropeRepository->isCasEurope($importCtll->getCountry());
                    $casIME = $this->imeRepository->isCasIME($importCtll->getReactionListPT());
                    $casDME = $this->dmeRepository->isCasDME($importCtll->getReactionListPT());
                    $EvRepID_11Chars = substr(rtrim($EVSafetyReportIdentifier), -11);
                    $ICSR_form_link =   'https://eudravigilance-human.ema.europa.eu/ev-web/api/reports/safetyreport/' .
                                        $EvRepID_11Chars .
                                        '?reportType=CIOMS&reportFormat=pdf';
                    $E2B_link = 'https://eudravigilance-human.ema.europa.eu/ev-web/api/reports/safetyreport/' .
                                $EvRepID_11Chars .
                                '?reportType=HUMAN_READABLE&reportFormat=html';
                    $Complete_Narrative_link =  'http://bi.eudra.org/xmlpserver/PHV%20EudraVigilance%20DWH%20(EVDAS)/_filters/PHV%20EVDAS/Templates/Data%20Warehouse%20Subgroup/Narrative/Narrative.xdo?_xpf=&_xt=Narrative&p_narrati=' .
                                                $EvRepID_11Chars .
                                                '&_xpt=1&_xf=rtf';
                    $susarEU->setICSRFormLink($ICSR_form_link);
                    $susarEU->setE2BLink($E2B_link);
                    $susarEU->setCompleteNarrativeLink($Complete_Narrative_link);
                    $susarEU->setEVSafetyReportIdentifier($EVSafetyReportIdentifier);
                    $susarEU->setDLPVersion($importCtll->getCaseVersion());
                    $susarEU->setWorldWideId($importCtll->getCaseReportNumber());
                    $susarEU->setNumEudract($importCtll->getStudyRegistrationN());
                    $susarEU->setSponsorstudynumb($importCtll->getSponsorStudyNumber());
                    $susarEU->setNarratif($importCtll->getNarrativeReportersCommentsAndSendersComments());
                    $susarEU->setNarratifNbCaractere($this->donneNarratifNbCaractere($importCtll->getNarrativePresent()));
                    // $susarEU->setPaysEtude($importCtll->getCountry());
                    $susarEU->setPaysSurvenue($importCtll->getCountry());
                    $susarEU->setCasEurope($casEurope);
                    $susarEU->setCasIME($casIME);
                    $susarEU->setCasDME($casDME);
                    $susarEU->setSeriousnessCriteria($this->donneGravite($importCtll));
                    $susarEU->setIsCaseSerious($importCtll->getSerious());
                    $susarEU->setPatientAgeGroup($importCtll->getAgeGroup());
                    // $susarEU->setPatientAge(patientAge);
                    if (is_numeric($importCtll->getAge())) {
                        $susarEU->setPatientAge($importCtll->getAge());
                    } else {
                        $susarEU->setPatientAge(null);
                    }
                    $susarEU->setPatientSex($importCtll->getSex());
                    $susarEU->setBirthDate($importCtll->getBirthDate());
                    $susarEU->setParentChild($importCtll->getParentChild());
                    $susarEU->setReceiveDate($importCtll->getReceiveDate());
                    $susarEU->setReceiptDate($importCtll->getReceiptDate());
                    $susarEU->setGatewayDate($importCtll->getGatewayDate());
                    $susarEU->setInitialsHeightWeight($importCtll->getInitialsHeightWeight());
                    $susarEU->setPrimarySourceQualification($importCtll->getPrimarySourceQualification());
                    $susarEU->setCasSusarEuV1(false);

                    // a faire :
                    // $susarEU->setPriorisation($this->priorisation->donneNiveauPriorisation());
                    $susarEU->setPriorisation(
                        $this->priorisation->donneNiveauPriorisation(
                            $importCtll,
                            $casEurope,
                            $casIME,
                            $casDME
                        )
                    );

                    $susarEU->setImportCtll($importCtll);
                    $susarEU->setCreatedAt($dateImport);
                    $susarEU->setUpdatedAt($dateImport);
                    $susarEU->setUtilisateurImport($authenticationUtils->getLastUsername());
                    $em->persist($susarEU);

                    // Import des médicaments
                    $this->importMedicaments($importCtll, $susarEU, $em, $dateImport);
                    // Import des médicaments
                    $this->importEffetsIndesirables($importCtll, $susarEU, $em, $dateImport);
                    // Import medical history
                    $this->importMedicalHistory($importCtll, $susarEU, $em, $dateImport);
                    // Import indications
                    $this->importIndications($importCtll, $susarEU, $em, $dateImport);

                    $this->nbOfInsertedSusar++;
                }
            }
            $em->flush();
        }

        return [
            'nbOfInsertedSusar' => $this->nbOfInsertedSusar,
            'nbOfInsertedMedic' => $this->nbOfInsertedMedic,
            'nbOfInsertedEffInd' => $this->nbOfInsertedEffInd,
            'nbOfInsertedMedHist' => $this->nbOfInsertedMedHist,
            'nbOfInsertedIndic' => $this->nbOfInsertedIndic
        ];
    }


    private function split_BR_BR($inputString)
    {
        $normalizedString = str_replace(",<BR><BR>", "<BR><BR>", $inputString);

        $resultArray = explode("<BR><BR>", $normalizedString);

        return $resultArray;
    }
    private function importMedicaments($importCtll, $susarEU, $em, $dateImport)
    {

        $tousMedicSuspInt = $importCtll->getSuspectInteractingEnhancedReportedDrugList();

        $tousMedicConc = $importCtll->getConcomitantNotAdministeredEnhancedReportedDrugList();

        if ($tousMedicSuspInt) {
            $tousMedicSuspIntArray = $this->split_BR_BR($tousMedicSuspInt);
            foreach ($tousMedicSuspIntArray as $medic) {
                $medicament = new Medicaments();
                $medicament->setSusar($susarEU);
                $medicament->setNomProduitBrut($medic);


                $parsingMedic = $this->parsingMedicaments->donneParsing($medic);
                if ($parsingMedic) {
                    $medicament->setSubstanceName($parsingMedic['substance']);
                    $medicament->setProductname($parsingMedic['produit']);
                    switch ($parsingMedic['drug_char']) {
                        case 'S':
                            $medicament->setProductcharacterization('Suspect');
                            break;
                        case 'C':
                            $medicament->setProductcharacterization('Concomitant');
                            break;
                        case 'I':
                            $medicament->setProductcharacterization('Interacting');
                            break;
                        default:
                            $medicament->setProductcharacterization('NA');
                            break;
                    }
                    $medicament->setMaladie($parsingMedic['indication_pt']);
                    $medicament->setStatutMedicApresEffet($parsingMedic['action_taken']);
                    $medicament->setDateDerniereAdmin($parsingMedic['start_date']);
                    $dateDerniereAdmin = \DateTime::createFromFormat('d/m/Y', $parsingMedic['start_date']);
                    if ($dateDerniereAdmin instanceof \DateTime) {
                        $medicament->setDateDerniereAdminFormatDate($dateDerniereAdmin);
                    } else {
                        $medicament->setDateDerniereAdminFormatDate(null);
                    }

                    // $medicament->setDateDerniereAdminFormatDate($dateDerniereAdmin);
                    $medicament->setDelaiAdministrationSurvenue($parsingMedic['duration']);
                    $medicament->setDosage($parsingMedic['dose']);
                    $medicament->setVoieAdmin($parsingMedic['route']);
                    $medicament->setComment($parsingMedic['comment']);
                }


                // dump($parsingMedic);
                $medicament->setCreatedAt($dateImport);
                $medicament->setUpdatedAt($dateImport);


                $em->persist($medicament);
                $this->nbOfInsertedMedic++;
            }
        }

        if ($tousMedicConc) {
            $tousMedicConcArray = $this->split_BR_BR($tousMedicConc);
            foreach ($tousMedicConcArray as $medic) {
                $medicament = new Medicaments();
                $medicament->setSusar($susarEU);
                $medicament->setNomProduitBrut($medic);

                $parsingMedic = $this->parsingMedicaments->donneParsing($medic);
                if ($parsingMedic) {
                    $medicament->setSubstanceName($parsingMedic['substance']);
                    $medicament->setProductname($parsingMedic['produit']);
                    switch ($parsingMedic['drug_char']) {
                        case 'S':
                            $medicament->setProductcharacterization('Suspect');
                            break;
                        case 'C':
                            $medicament->setProductcharacterization('Concomitant');
                            break;
                        case 'I':
                            $medicament->setProductcharacterization('Interacting');
                            break;
                        default:
                            $medicament->setProductcharacterization('NA');
                            break;
                    }
                    $medicament->setMaladie($parsingMedic['indication_pt']);
                    $medicament->setStatutMedicApresEffet($parsingMedic['action_taken']);
                    $medicament->setDateDerniereAdmin($parsingMedic['start_date']);
                    $dateDerniereAdmin = \DateTime::createFromFormat('d/m/Y', $parsingMedic['start_date']);
                    if ($dateDerniereAdmin instanceof \DateTime) {
                        $medicament->setDateDerniereAdminFormatDate($dateDerniereAdmin);
                    } else {
                        $medicament->setDateDerniereAdminFormatDate(null);
                    }
                    // $medicament->setDateDerniereAdminFormatDate($dateDerniereAdmin);
                    $medicament->setDelaiAdministrationSurvenue($parsingMedic['duration']);
                    $medicament->setDosage($parsingMedic['dose']);
                    $medicament->setVoieAdmin($parsingMedic['route']);
                    $medicament->setComment($parsingMedic['comment']);
                }

                $medicament->setCreatedAt($dateImport);
                $medicament->setUpdatedAt($dateImport);
                $em->persist($medicament);
                $this->nbOfInsertedMedic++;
            }
        }
    }

    private function importEffetsIndesirables($importCtll, $susarEU, $em, $dateImport)
    {

        $tousEffetInd = $importCtll->getReactionListPT();

        if ($tousEffetInd) {
            $tousEffetIndArray = $this->split_BR_BR($tousEffetInd);
            foreach ($tousEffetIndArray as $effetInd) {
                $effetIndesirable = new EffetsIndesirables();
                $effetIndesirable->setSusar($susarEU);
                $effetIndesirable->setReactionListPTCTLL($effetInd);

                $parsingEffInd = $this->parsingMedicaments->parseReactionListPT($effetInd);


                if ($parsingEffInd) {
                    $reactionListPT = $parsingEffInd['ReactionListPT'];

                    if ($reactionListPT) {
                        $codePt = $this->meddraMdHierarchyRepository->findCodePtByPtName($reactionListPT);
                        if ($codePt && count($codePt) == 1) {
                            $effetIndesirable->setCodereactionmeddrapt($codePt[0]['PtCode']);
                        }
                        $effetIndesirable->setReactionListPT($reactionListPT);
                    }

                    $effetIndesirable->setOutcome($parsingEffInd['Outcome']);
                    $effetIndesirable->setDate($parsingEffInd['Date']);

                    $date = \DateTime::createFromFormat('d/m/Y', $parsingEffInd['Date']);
                    if ($date instanceof \DateTime) {
                        $effetIndesirable->setDateFormatDate($date);
                    } else {
                        $effetIndesirable->setDateFormatDate(null);
                    }
                    $effetIndesirable->setDuration($parsingEffInd['Duration']);
                }


                $effetIndesirable->setCreatedAt($dateImport);
                $effetIndesirable->setUpdatedAt($dateImport);
                $em->persist($effetIndesirable);
                $this->nbOfInsertedEffInd++;
            }
        }
    }

    private function importMedicalHistory($importCtll, $susarEU, $em, $dateImport)
    {
        $tousMedHist = $importCtll->getStructuredMedicalHistory();

        if ($tousMedHist) {
            $tousMedHistory = $this->split_BR_BR($tousMedHist);
            foreach ($tousMedHistory as $MedHistory) {
                $medicalHistory = new MedicalHistory();
                $medicalHistory->setSusar($susarEU);
                $medicalHistory->setMedicalHistoryCTLL($MedHistory);

                $parsingMedHist = $this->parsingMedicaments->parseMedHist($MedHistory);

                if ($parsingMedHist) {

                    $medicalHistory->setDisease($parsingMedHist['Disease']);
                    $medicalHistory->setContinuing($parsingMedHist['Continuing']);
                    $medicalHistory->setComment($parsingMedHist['Comment']);
                }

                $medicalHistory->setCreatedAt($dateImport);
                $medicalHistory->setUpdatedAt($dateImport);
                $em->persist($medicalHistory);
                $this->nbOfInsertedMedHist++;
            }
        }
    }
    private function importIndications($importCtll, $susarEU, $em, $dateImport)
    {
        $tousIndications = $importCtll->getIndicationsPTOfTheDrugOfInterestAsReportedInTheICSR();

        if ($tousIndications) {
            $tousIndications = $this->split_BR_BR($tousIndications);
            foreach ($tousIndications as $Indications) {
                $indication = new Indications();
                $indication->setSusar($susarEU);
                $indication->setIndicationCTLL($Indications);

                $parsingIndications = $this->parsingMedicaments->parseIndication($Indications);

                if ($parsingIndications) {

                    $indication->setProductName($parsingIndications['product_name']);
                    $indication->setProductIndicationsEng($parsingIndications['product_indications_eng']);
                }

                $indication->setCreatedAt($dateImport);
                $indication->setUpdatedAt($dateImport);
                $em->persist($indication);
                $this->nbOfInsertedIndic++;
            }
        }
    }

    private function donneGravite($importCtll)
    {
        $gravite = '';
        // $serious= $importCtll->getSerious();
        // $death= $importCtll->getSeriousnessDeath();
        // $lifethreatening= $importCtll->getSeriousnessLifethreatening();
        // $hospitalization= $importCtll->getSeriousnessHospitalisation(); 
        // $disability= $importCtll->getSeriousnessDisabling();
        // $congenitalAnomaly= $importCtll->getSeriousnessCongenitalAnomaly();
        // $otherSeriousness= $importCtll->getSeriousnessOther();

        // dump($serious);
        // dump($death);
        // dump($lifethreatening);
        // dump($hospitalization); 
        // dump($disability);
        // dump($congenitalAnomaly);
        // dump($otherSeriousness);
        if ($importCtll->getSeriousnessDeath() == 'Yes') {
            $gravite = $gravite === '' ? 'Death' : $gravite . '<BR>Death';
        }
        if ($importCtll->getSeriousnessLifethreatening() == 'Yes') {
            $gravite = $gravite === '' ? 'Life Threatening' : $gravite . '<BR>Life Threatening';
        }
        if ($importCtll->getSeriousnessHospitalisation() == 'Yes') {
            $gravite = $gravite === '' ? 'Hospitalisation or prolongation of existing Hospitalisation' : $gravite . '<BR>Hospitalisation or prolongation of existing Hospitalisation';
        }
        if ($importCtll->getSeriousnessDisabling() == 'Yes') {
            $gravite = $gravite === '' ? 'Persistent or significant Disability / Incapacity' : $gravite . '<BR>Persistent or significant Disability / Incapacity';
        }
        if ($importCtll->getSeriousnessCongenitalAnomaly() == 'Yes') {
            $gravite = $gravite === '' ? 'Congenital Anomaly / Birth defect' : $gravite . '<BR>Congenital Anomaly / Birth defect';
        }
        if ($importCtll->getSeriousnessOther() == 'Yes') {
            $gravite = $gravite === '' ? 'Other medically important condition' : $gravite . '<BR>Other medically important condition';
        }

        return $gravite;
    }

    private function donneNarratifNbCaractere($narPres)
    {
        $narratifNbCaractere = 0; // Valeur par défaut si rien n'est trouvé

        if ($narPres) {
            // Utiliser une expression régulière pour extraire le nombre après la première parenthèse
            if (preg_match('/\((\d+)/', $narPres, $matches)) {
                $narratifNbCaractere = (int) $matches[1]; // Convertir le résultat en entier
            }
        }

        return $narratifNbCaractere;
    }
}
