<?php

namespace App\Service;

use App\Entity\SusarEU;
use App\Entity\Indications;
use App\Entity\Medicaments;
use App\Entity\SubstancePt;
use Psr\Log\LoggerInterface;
use App\Service\Priorisation;
use App\Entity\MedicalHistory;
use App\Repository\DMERepository;
use App\Repository\IMERepository;
use App\Entity\EffetsIndesirables;
use App\Service\ParsingMedicaments;
use App\Repository\SusarEURepository;
use App\Entity\IntervenantSubstanceDMM;
use App\Repository\ImportCtllRepository;
use App\Repository\PaysEuropeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MeddraMdHierarchyRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
    private int $nbSusarAttribue = 0;
    private int $nbSusarNonAttribue = 0;
    private int $nbMedicAttribue = 0;
    private $gatewayDate = [];
    private $idNonAttribue = [];
    private $lst_productname = [];
    private $lst_substancename = [];
    private $lst_productname_nonAttribue = [];
    private $lst_substancename_nonAttribue = [];
    private LoggerInterface $logger;

    public function __construct(
        ImportCtllRepository $importCtllRepository,
        SusarEURepository $susarEURepository,
        ParsingMedicaments $parsingMedicaments,
        Priorisation $priorisation,
        PaysEuropeRepository $paysEuropeRepository,
        DMERepository $dmeRepository,
        IMERepository $imeRepository,
        MeddraMdHierarchyRepository $meddraMdHierarchyRepository,
        LoggerInterface $logger,
    ) {
        $this->importCtllRepository = $importCtllRepository;
        $this->susarEURepository = $susarEURepository;
        $this->parsingMedicaments = $parsingMedicaments;
        $this->priorisation = $priorisation;
        $this->paysEuropeRepository = $paysEuropeRepository;
        $this->dmeRepository = $dmeRepository;
        $this->imeRepository = $imeRepository;
        $this->meddraMdHierarchyRepository = $meddraMdHierarchyRepository;
        $this->logger = $logger;
    }

    public function importExcelVersTbSusarEu(int $idImportCtllFicExcel, EntityManagerInterface $em, AuthenticationUtils $authenticationUtils, $user )
    {
        $importCtlls = $this->importCtllRepository->findByIdImport($idImportCtllFicExcel);


        if ($importCtlls) {
            if ($user) {
                $userName = $user->getUserName(); // Appelle la méthode getUserName() de l'entité User
                // dd($userName); // Affiche le userName pour vérifier
            } else {
                throw $this->createAccessDeniedException('Utilisateur non connecté.');
            }
            $em->beginTransaction();
            // time out a 10 minutes pour l'import excel
            set_time_limit(600); 


            foreach ($importCtlls as $importCtll) {
                $EVSafetyReportIdentifier = $importCtll->getEVSafetyReportIdentifier();
                if (!$this->susarEURepository->existeEV_SafetyReportIdentifier($EVSafetyReportIdentifier)) {
                    $importCtll->setSusarDejaExistant(false);
                    $em->persist($importCtll);
                    $dateImport = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));
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
                    $susarEU->setSender($importCtll->getSender());
                    // $susarEU->setNarratif($importCtll->getNarrativeReportersCommentsAndSendersComments());
                    $susarEU->setNarratif(str_replace('<BR>', "\n", 
                                            $importCtll->getNarrativeReportersCommentsAndSendersComments()));
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

                    $gatewayDate = $importCtll->getGatewayDate();
                    if ($gatewayDate instanceof \DateTimeInterface) {
                        $gatewayDateStr = $gatewayDate->format('d/m/Y');
                    } elseif (!empty($gatewayDate)) {
                        // Si c'est une chaîne, essayez de la convertir
                        $dateObj = \DateTime::createFromFormat('Y-m-d', $gatewayDate);
                        $gatewayDateStr = $dateObj ? $dateObj->format('d/m/Y') : $gatewayDate;
                    } else {
                        $gatewayDateStr = null;
                    }
                    
                    if ($gatewayDateStr && !in_array($gatewayDateStr, $this->gatewayDate, true)) {
                        $this->gatewayDate[] = $gatewayDateStr;
                    }

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
                    $susarEU->setUtilisateurImport($userName);
                    $em->persist($susarEU);
                    // $em->flush();   // On flush ici pour que l'id du susar soit disponible pour les médicaments et effets indésirables
                    // Import des médicaments
                    $lstMedSupInter = $this->importMedicaments($importCtll, $susarEU, $em, $dateImport);

                    if (is_array($lstMedSupInter) && empty($lstMedSupInter)) {
                        // On n'a pas pu importer de médicaments, on supprime le susar
                        foreach ($susarEU->getMedicament() as $medicament) {
                            $em->remove($medicament);
                            $this->nbOfInsertedMedic--;
                        }
                        $em->remove($susarEU);
                        $importCtll->setSusarAttribue(false);

                        $this->lst_productname_nonAttribue[] = $this->lst_productname;
                        $this->lst_substancename_nonAttribue[] = $this->lst_substancename;
                        // $em->flush();
                        $this->logger->error('Aucun médicament n\'a pu être importé pour le susar, ce susar ne sera pas sauvegardé : ' . $susarEU->getEVSafetyReportIdentifier());
                        continue; // On passe au susar suivant
                    }
                    // Import des médicaments
                    $lstEffInd = $this->importEffetsIndesirables($importCtll, $susarEU, $em, $dateImport);
                    // Création des lignes dans la table substance_pt
                    
                    $this->creationSubstancePt($lstMedSupInter, $lstEffInd, $susarEU, $em, $dateImport);
                    // Import medical history
                    $this->importMedicalHistory($importCtll, $susarEU, $em, $dateImport);
                    // Import indications
                    $this->importIndications($importCtll, $susarEU, $em, $dateImport);
                    
                    $this->nbOfInsertedSusar++;
                } else {
                    // ce cas est déjà dans la base, on flag l'import comme "deja existant"
                    $importCtll->setSusarDejaExistant(true);
                    $importCtll->setSusarAttribue(false);
                    $em->persist($importCtll);
                }
            }
            $em->flush();
            $em->commit();
        }

        return [
            'nbOfInsertedSusar' => $this->nbOfInsertedSusar,
            'nbOfInsertedMedic' => $this->nbOfInsertedMedic,
            'nbOfInsertedEffInd' => $this->nbOfInsertedEffInd,
            'nbOfInsertedMedHist' => $this->nbOfInsertedMedHist,
            'nbOfInsertedIndic' => $this->nbOfInsertedIndic,
            'nbSusarAttribue' => $this->nbSusarAttribue,
            'nbSusarNonAttribue' => $this->nbSusarNonAttribue,
            'nbMedicAttribue' => $this->nbMedicAttribue,
            'gatewayDate' => $this->gatewayDate,
            'idNonAttribue' => $this->idNonAttribue,
            'lst_productname_nonAttribue' => $this->lst_productname_nonAttribue,
            'lst_substancename_nonAttribue' => $this->lst_substancename_nonAttribue,
        ];
    }


    private function split_BR_BR($inputString)
    {
        $normalizedString = str_replace(",<BR><BR>", "<BR><BR>", $inputString);

        $resultArray = explode("<BR><BR>", $normalizedString);

        return $resultArray;
    }
    private function importMedicaments($importCtll, $susarEU, $em, $dateImport): array
    {
        $nbMedicAttribue = 0;
        $lstMedSupInter = [];
        $this->lst_productname = [$importCtll->getId()];
        $this->lst_substancename = [$importCtll->getId()];

        $tousMedicSuspInt = $importCtll->getSuspectInteractingEnhancedReportedDrugList();

        $tousMedicConc = $importCtll->getConcomitantNotAdministeredEnhancedReportedDrugList();

        if ($tousMedicSuspInt) {
            $tousMedicSuspIntArray = $this->split_BR_BR($tousMedicSuspInt);
            foreach ($tousMedicSuspIntArray as $medic) {
                $medicament = new Medicaments();
                // $medicament->setSusar($susarEU);
                $susarEU->addMedicament($medicament);
                $medicament->setNomProduitBrut(substr($medic, 0, 1000));

                $parsingMedic = $this->parsingMedicaments->donneParsing($medic);
                if ($parsingMedic) {
                    $substancePourRecherche = null;
                    // Si le médicament ne contient pas de chaine de caractere entre crochet, on n'a pas de nom de substance, on prend le nom du produit
                    if (($parsingMedic['substance']) === null || $parsingMedic['substance'] === '') {
                        $medicament->setSubstanceName(substr($parsingMedic['produit'] ?? '', 0, 255));
                        $substancePourRecherche = $parsingMedic['produit'];
                        $this->lst_substancename[] = $parsingMedic['produit'];
                    } else {
                        $medicament->setSubstanceName(substr($parsingMedic['substance'] ?? '', 0, 255));
                        $substancePourRecherche = $parsingMedic['substance'];
                        $this->lst_substancename[] = $parsingMedic['substance'];
                    }
                    $lstMedSupInter[] = $substancePourRecherche;
                    $medicament->setProductname(substr($parsingMedic['produit'] ?? '', 0, 255));
                    $this->lst_productname[] = $parsingMedic['produit'];
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
                    $medicament->setMaladie(substr($parsingMedic['indication_pt'] ?? '', 0, 255));
                    $medicament->setStatutMedicApresEffet(substr($parsingMedic['action_taken'] ?? '', 0, 255));
                    $medicament->setDateDerniereAdmin(substr($parsingMedic['start_date'] ?? '', 0, 255));
                    $dateDerniereAdmin = \DateTime::createFromFormat('d/m/Y', $parsingMedic['start_date']);
                    if ($dateDerniereAdmin instanceof \DateTime) {
                        $medicament->setDateDerniereAdminFormatDate($dateDerniereAdmin);
                    } else {
                        $medicament->setDateDerniereAdminFormatDate(null);
                    }
                    
                    // $medicament->setDateDerniereAdminFormatDate($dateDerniereAdmin);
                    $medicament->setDelaiAdministrationSurvenue(substr($parsingMedic['duration'] ?? '', 0, 255));
                    $medicament->setDosage(substr($parsingMedic['dose'] ?? '', 0, 255));
                    $medicament->setVoieAdmin(substr($parsingMedic['route'] ?? '', 0, 255));
                    if (isset($parsingMedic['comment'])) {
                        $medicament->setComment(substr($parsingMedic['comment'], 0, 255));
                    } 

                    // attribution de l'évaluateur a ce médicament
                    if ($substancePourRecherche) {
                        // $IntervenantSubstanceDMM = $em->getRepository(IntervenantSubstanceDMM::class)->findByInHL_SA($parsingMedic['substance']);

                        $IntervenantSubstanceDMM = $em->getRepository(IntervenantSubstanceDMM::class)->findContainingHL_SA($substancePourRecherche);
                        if ($IntervenantSubstanceDMM) {
                            if (count($IntervenantSubstanceDMM) === 1) {
                                // Il n'y a qu'un seul intervenant-substance, on l'attribue au médicament
                            } else {

                                $this->logger->warning('Il y a plusieurs intervenants-substance pour l\'ID suivant de la table importCtll : '
                                                        . $importCtll->getId()
                                                        . '. Pour la substance suivante : ' . $substancePourRecherche . '.');
                                // Lever une exception Symfony à la place de dump et dd
                                // throw new HttpException(
                                //     500, // Code HTTP 500 pour une erreur interne du serveur
                                //     sprintf(
                                //         "Il y a plusieurs intervenants-substance pour l'ID suivant de la table importCtll : %d. Pour la substance suivante : %s. Le traitement est arrêté, veuillez vérifier.",
                                //         $importCtll->getId(),
                                //         $substancePourRecherche
                                //     )
                                // );
                                // // On annule la transaction
                                // $em->rollback();

                            }
                            $nbMedicAttribue++;
                            // On tag cette ligne comme "attribuée" dans la table d'import
                            $importCtll->setSusarAttribue(true);
                            $em->persist($importCtll);
                            // Il faut lier ce médicament à l'intervenant substance DMM
                            $medicament->setIntervenantSubstanceDMM($IntervenantSubstanceDMM[0]);
                            $medicament->setTypeSaMSMono($IntervenantSubstanceDMM[0]->getTypeSaMSMono());

                            // Il faut lier l'intervenant-substance au susar passé en paramètre
                            $susarEU->addIntervenantSubstanceDMM($IntervenantSubstanceDMM[0]);
                        }
                    }
                }


                // dump($parsingMedic);
                $medicament->setCreatedAt($dateImport);
                $medicament->setUpdatedAt($dateImport);

                $em->persist($medicament);
                $this->nbOfInsertedMedic++;

                // return [
                //     'nbSusarAttribue' => $nbSusarAttribue,
                //     'nbMedicAttribue' => $nbMedicAttribue,
                // ];
            }

            if ($nbMedicAttribue == 0) {
                // Aucun médicament n'a été attribué, on va reboucler sur les médicaments de ce susar, en essayant de faire l'attribution d'évaluateu
                //   grace au Productname
                foreach ($susarEU->getMedicament() as $medicament) {

                    $substancePourRecherche = $medicament->getProductname();

                    // attribution de l'évaluateur a ce médicament
                    if ($substancePourRecherche) {
                        // $IntervenantSubstanceDMM = $em->getRepository(IntervenantSubstanceDMM::class)->findByInHL_SA($parsingMedic['substance']);

                        $IntervenantSubstanceDMM = $em->getRepository(IntervenantSubstanceDMM::class)->findContainingHL_SA($substancePourRecherche);
                        if ($IntervenantSubstanceDMM) {
                            if (count($IntervenantSubstanceDMM) === 1) {
                                // Il n'y a qu'un seul intervenant-substance, on l'attribue au médicament
                            } else {

                                $this->logger->warning('Il y a plusieurs intervenants-substance pour l\'ID suivant de la table importCtll : '
                                                        . $importCtll->getId()
                                                        . '. Pour la substance suivante : ' . $substancePourRecherche . '.');

                            }
                            $nbMedicAttribue++;
                            // On tag cette ligne comme "attribuée" dans la table d'import
                            $importCtll->setSusarAttribue(true);
                            $em->persist($importCtll);
                            // Il faut lier ce médicament à l'intervenant substance DMM
                            $medicament->setIntervenantSubstanceDMM($IntervenantSubstanceDMM[0]);
                            $medicament->setTypeSaMSMono($IntervenantSubstanceDMM[0]->getTypeSaMSMono());

                            // Il faut lier l'intervenant-substance au susar passé en paramètre
                            $susarEU->addIntervenantSubstanceDMM($IntervenantSubstanceDMM[0]);

                            // On stocke l'ancien nom de la substance avant modification
                            $medicament->setSubstancenameAvantModifAttribProductname($medicament->getSubstancename());
                            $medicament->setSubstancename($medicament->getProductname());

                            $em->persist($medicament);
                        }
                    }
                }
            }
            if ($nbMedicAttribue == 0) {
                // On n'a pas réussi a attribuer d'évaluateur, on va essayer d'annuler l'enregistrement de ce susar
                $this->idNonAttribue[] = $importCtll->getId();
                $this->nbSusarNonAttribue++;
                return [];
            }

            $nbSusarAttribue = $nbMedicAttribue > 0 ? 1 : 0;

            $this->nbSusarAttribue = $this->nbSusarAttribue  + $nbSusarAttribue;
            $this->nbMedicAttribue = $this->nbMedicAttribue + $nbMedicAttribue;
        }

        if ($tousMedicConc) {
            //
            $tousMedicConcArray = $this->split_BR_BR($tousMedicConc);
            foreach ($tousMedicConcArray as $medic) {
                $medicament = new Medicaments();
                $medicament->setSusar($susarEU);
                $medicament->setNomProduitBrut(substr($medic, 0, 1000));

                $parsingMedic = $this->parsingMedicaments->donneParsing($medic);
                if ($parsingMedic) {
                    $medicament->setSubstanceName(substr($parsingMedic['substance'] ?? '', 0, 255));
                    $medicament->setProductname(substr($parsingMedic['produit'] ?? '', 0, 255));
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
                    $medicament->setMaladie(substr($parsingMedic['indication_pt'] ?? '', 0, 255));
                    $medicament->setStatutMedicApresEffet(substr($parsingMedic['action_taken'] ?? '', 0, 255));
                    $medicament->setDateDerniereAdmin(substr($parsingMedic['start_date'] ?? '', 0, 255));
                    $dateDerniereAdmin = \DateTime::createFromFormat('d/m/Y', $parsingMedic['start_date']);
                    if ($dateDerniereAdmin instanceof \DateTime) {
                        $medicament->setDateDerniereAdminFormatDate($dateDerniereAdmin);
                    } else {
                        $medicament->setDateDerniereAdminFormatDate(null);
                    }
                    // $medicament->setDateDerniereAdminFormatDate($dateDerniereAdmin);
                    $medicament->setDelaiAdministrationSurvenue(substr($parsingMedic['duration'] ?? '', 0, 255));
                    $medicament->setDosage(substr($parsingMedic['dose'] ?? '', 0, 255));
                    $medicament->setVoieAdmin(substr($parsingMedic['route'] ?? '', 0, 255));
                    if (isset($parsingMedic['comment'])) {
                        $medicament->setComment(substr($parsingMedic['comment'], 0, 255));
                    }
                }

                $medicament->setCreatedAt($dateImport);
                $medicament->setUpdatedAt($dateImport);
                $em->persist($medicament);
                $this->nbOfInsertedMedic++;
            }
        }
        if ($nbMedicAttribue === 0) {
            // pas d'évaluateur attribué a ce susar, on l'attribut à l'évaluateur '_non attribué_'
            $IntervenantSubstanceDMM = $em->getRepository(IntervenantSubstanceDMM::class)->findEvaluateur_non_attribue_();
            if ($IntervenantSubstanceDMM) {
                // il faut lier l'intervenant-substance au susar passé en paramètre
                $susarEU->addIntervenantSubstanceDMM($IntervenantSubstanceDMM[0]);
            }
        }
        return $lstMedSupInter;
    }

    private function importEffetsIndesirables($importCtll, $susarEU, $em, $dateImport): array
    {

        $tousEffetInd = $importCtll->getReactionListPT();
        $lstEffInd = [];
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
                            $lstEffInd[] = [
                                'PtCode' => $codePt[0]['PtCode'],
                                'ReactionListPT' => $reactionListPT,
                            ];
                        } else {
                            $lstEffInd[] = [
                                'PtCode' => null,
                                'ReactionListPT' => $reactionListPT,
                            ];
                        }
                        $effetIndesirable->setReactionListPT(substr($reactionListPT, 0, 255));
                    }

                    $effetIndesirable->setOutcome(substr($parsingEffInd['Outcome'] ?? '', 0, 255));
                    $effetIndesirable->setDate(substr($parsingEffInd['Date'] ?? '', 0, 255));

                    $date = \DateTime::createFromFormat('d/m/Y', $parsingEffInd['Date']);
                    if ($date instanceof \DateTime) {
                        $effetIndesirable->setDateFormatDate($date);
                    } else {
                        $effetIndesirable->setDateFormatDate(null);
                    }
                    $effetIndesirable->setDuration(substr($parsingEffInd['Duration'] ?? '', 0, 255));
                }


                $effetIndesirable->setCreatedAt($dateImport);
                $effetIndesirable->setUpdatedAt($dateImport);
                $em->persist($effetIndesirable);
                $this->nbOfInsertedEffInd++;
            }
        }
        return $lstEffInd;
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

                    $medicalHistory->setDisease(substr($parsingMedHist['Disease'] ?? '', 0, 255));
                    $medicalHistory->setContinuing(substr($parsingMedHist['Continuing'] ?? '', 0, 255));

                    if (isset($parsingMedHist['Comment'])) {
                        $medicalHistory->setComment(substr($parsingMedHist['Comment'], 0, 255));
                    }
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

                    $indication->setProductName(substr($parsingIndications['product_name'] ?? '', 0, 255));
                    $indication->setProductIndicationsEng(substr($parsingIndications['product_indications_eng'] ?? '', 0, 255));
                }

                $indication->setCreatedAt($dateImport);
                $indication->setUpdatedAt($dateImport);
                $em->persist($indication);
                $this->nbOfInsertedIndic++;
            }
        }
    }

    /**
     * Check si les différents couples substance/effet indésirable sont présents dans la base de données
     * Si il n'existe pas, on les crée et on les lie au susar
     * Si il existe, on les lie au susar
     *
     * @param [type] $lstMedSupInter
     * @param [type] $lstEffInd
     * @param [type] $susarEU
     * @param [type] $em
     * @param [type] $dateImport
     * @return void
     */
    private function creationSubstancePt($lstMedSupInter,$lstEffInd, $susarEU, $em, $dateImport)
    {
        // dump($susarEU->getId());
        if(count($lstMedSupInter)>0 && count($lstEffInd)>0){
            foreach ($lstMedSupInter as $substance) {
                foreach ($lstEffInd as $effetInd) {
                    $substancePt = $em->getRepository(SubstancePt::class)->findByActiveSubstanceAndReactionMeddraPt($substance, $effetInd['ReactionListPT']);
                    if (!$substancePt) {
                        // Si la substance et l'effet indésirable n'existent pas, on les crée
                        $substancePt = new SubstancePt();
                        $substancePt->setActiveSubstanceHighLevel(substr($substance, 0, 1000));
                        $substancePt->setReactionmeddrapt(substr($effetInd['ReactionListPT'], 0, 255));
                        $substancePt->setCodereactionmeddrapt($effetInd['PtCode']);
                        $substancePt->setCreatedAt($dateImport);
                        $substancePt->setUpdatedAt($dateImport);
                        $em->persist($substancePt);
                        $susarEU->addSubstancePt($substancePt);
                    } else {
                        if ($em->getRepository(SubstancePt::class)->isLinkedToSusarEU($substancePt, $susarEU) === false) {
                            // On lie le susar à la substance et à l'effet indésirable
                            $susarEU->addSubstancePt($substancePt);
                            $em->persist($susarEU);
                        }
                    }


                }
            }
        }
    }
    private function donneGravite($importCtll)
    {
        $gravite = '';

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
