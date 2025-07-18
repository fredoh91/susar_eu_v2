<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\SusarEU;
use App\Entity\ImportCtll;
use Psr\Log\LoggerInterface;
use App\Form\UploadExcelCTLLType;
use App\Entity\ImportCtllFicExcel;
use App\Service\ImportVersSusarEu;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
// use PhpOffice\PhpSpreadsheet\RichText\RichText;

final class ImportExcelCTLLController extends AbstractController
{
    private int $importedRowsCount = 0;
    private int $idImportCtllFicExcel = -1;
    private ImportVersSusarEu $importVersSusarEu;
    private array $nbDonneesInserees;
    private LoggerInterface $logger;
    private string $nomFichierExcel = '';

    public function __construct(ImportVersSusarEu $importVersSusarEu, LoggerInterface $logger,)
    {
        $this->importVersSusarEu = $importVersSusarEu;
        $this->nbDonneesInserees = [];
        $this->logger = $logger;
    }

    #[Route('/import_excel_ctll', name: 'app_import_excel_ctll')]
    public function upload_excel_ctll(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $em, AuthenticationUtils $authenticationUtils): Response
    {
        $form = $this->createForm(UploadExcelCTLLType::class);
        $form->handleRequest($request);

        // Initialisation la variables envoyées a twig 
        $dureeImport = null;
        $nonAttribues = 0;

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $FicExcel */
            $FicExcel = $form->get('FicExcel')->getData();

            if ($FicExcel) {
                // Démarrer le chronomètre
                $startTime = microtime(true);



                $user = $this->getUser(); // Récupère l'utilisateur connecté
                if ($user) {
                    $userName = $user->getUserName(); // Appelle la méthode getUserName() de l'entité User
                    // dd($userName); // Affiche le userName pour vérifier
                } else {
                    throw $this->createAccessDeniedException('Utilisateur non connecté.');
                }
                // dd($this->fichierExcelValide($FicExcel));
                // On check si dans la cellule A1, on a bien la chaîne de caractère : "SafetyReport Key"
                if (!$this->fichierExcelValide($FicExcel)) {
                    // dump('Fichier Excel en erreur');
                    $this->logger->warning('Le fichier Excel suivant n\'est pas valide. Vérifiez qu\'il s\'agit bien du fichier CTLL téléchargé via : Export/Data/Excel.', [
                        'fileName' => $FicExcel->getClientOriginalName(),
                        'user' => $userName,
                    ]);
                    $this->addFlash('error', 'Le fichier Excel n\'est pas valide. Vérifiez qu\'il s\'agit bien du fichier CTLL téléchargé via : Export/Data/Excel.');


                    return $this->redirectToRoute('app_import_excel_ctll');
                }

                $importCtllFicExcel = $this->importExcelVersTbImportCtll($FicExcel, $em, $authenticationUtils);
                $this->nbDonneesInserees = $this->importVersSusarEu->importExcelVersTbSusarEu(
                    $this->idImportCtllFicExcel,
                    $em,
                    $authenticationUtils,
                    $user
                );

                $this->nbDonneesInserees = array_merge(
                    ['nbOfExcelRow' => $this->importedRowsCount],
                    $this->nbDonneesInserees
                );

                // $this->finalizeImport($FicExcel, $importCtllFicExcel, $em);
                // Ici on mettra a jour l'entité ImportCtllFicExcel avec le nombre de lignes insérées

                // dump($this->nbDonneesInserees);

                $importCtllFicExcel->setNbInsertedSusar($this->nbDonneesInserees['nbOfInsertedSusar'])
                    ->setNbInsertedMedic($this->nbDonneesInserees['nbOfInsertedMedic'])
                    ->setNbInsertedEffInd($this->nbDonneesInserees['nbOfInsertedEffInd'])
                    ->setNbInsertedMedHist($this->nbDonneesInserees['nbOfInsertedMedHist'])
                    ->setNbInsertedIndic($this->nbDonneesInserees['nbOfInsertedIndic'])
                    ->setNbSusarAttribue($this->nbDonneesInserees['nbSusarAttribue'])
                    ->setNbSusarNonAttribue($this->nbDonneesInserees['nbSusarNonAttribue'])
                    ->setNbMedicAttribue($this->nbDonneesInserees['nbMedicAttribue'])
                    ->setGatewayDate($this->nbDonneesInserees['gatewayDate'])
                    ->setIdNonAttribue($this->nbDonneesInserees['idNonAttribue'])
                    ;

                $em->flush();
                $em->clear();
                // dump($this->idImportCtllFicExcel);
                $nonAttribues = $em->getRepository(ImportCtll::class)->donneNonAttribue($this->idImportCtllFicExcel);
                // dump($nonAttribues);
                // $susarNonAttribues = $em->getRepository(SusarEU::class)->findSusarByEVSafetyReportIdentifier('EU-EC-10019020020');
                // dump($susarNonAttribues);
                // Arrêter le chronomètre
                $endTime = microtime(true);
                $dureeImport = $endTime - $startTime; // Calculer la durée en secondes
            }
        }

        return $this->render('import_excel_ctll/upload_excel_ctll.html.twig', [
            'form' => $form->createView(),
            'nbDonneesInserees' => $this->nbDonneesInserees,
            'dureeImport' => $dureeImport,
            'nonAttribues' => $nonAttribues,
            'nomFichierExcel' => $this->nomFichierExcel
        ]);
    }

    #[Route('/import_excel_ctll_test', name: 'app_import_excel_ctll_test')]
    public function upload_excel_ctll_test(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $em, AuthenticationUtils $authenticationUtils): Response
    {
        $form = $this->createForm(UploadExcelCTLLType::class);
        $form->handleRequest($request);

        $nonAttribues = $em->getRepository(ImportCtll::class)->donneNonAttribue(1);
        // dump($nonAttribues);

        return $this->render('import_excel_ctll/upload_excel_ctll_test.html.twig', [
            'form' => $form->createView(),
            'nonAttribues' => $nonAttribues,
        ]);
    }


    /**
     * Permet de vérifier si le fichier Excel est valide.
     * ex. il ne s'agit pas du fichier CTTL avec le cartouche de l'EMA
     *
     * @param UploadedFile $FicExcel
     * @return Bool
     */
    private function fichierExcelValide(UploadedFile $FicExcel): Bool
    {
        $inputFileName = $FicExcel->getRealPath();
        $spreadsheet = IOFactory::load($inputFileName);
        $activeWorksheet = $spreadsheet->getActiveSheet();
        // $highestRow = $activeWorksheet->getHighestRow();

        // Vérifier si la première cellule contient "SafetyReport Key"

        $cellValue =  $activeWorksheet->getCell('A1')->getValue();

        if ($cellValue === null || $cellValue === '') {
            $this->logger->warning('La cellule est vide.');
            return false;
        }

        if ($cellValue instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
            $texteCellule = $cellValue->getRichTextElements();
            if (is_array($texteCellule) && isset($texteCellule[0])) {
                // dump($texteCellule[0]->getText());
                if ($texteCellule[0]->getText() !== 'SafetyReport Key') {
                    $this->logger->warning('La cellule A1 contient une valeur différente de \'SafetyReport Key\' : ' . 
                                            $texteCellule[0]->getText());
                    return false;
                }
            } else {
                $this->logger->warning('La cellule A1 contient du texte enrichi, mais aucun élément n\'est disponible.');
                return false;
            }
        } else {
            // $texteCellule = $cellValue; // Affiche la valeur brute si ce n'est pas du texte enrichi
            if ($cellValue !== 'SafetyReport Key') {
                    $this->logger->warning('La cellule A1 contient une valeur différente de \'SafetyReport Key\' : ' . 
                                            $cellValue);
                return false;
            }
        }
        return true;
    }


    private function importExcelVersTbImportCtll(UploadedFile $FicExcel, EntityManagerInterface $em, AuthenticationUtils $authenticationUtils): ImportCtllFicExcel
    {
        $inputFileName = $FicExcel->getRealPath();
        $spreadsheet = IOFactory::load($inputFileName);
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $highestRow = $activeWorksheet->getHighestRow();


        $importCtllFicExcel = $this->createImportCtllFicExcel($FicExcel, $inputFileName, $authenticationUtils);

        $this->processWorksheetRows($activeWorksheet, $highestRow, $importCtllFicExcel, $em);

        $this->finalizeImport($FicExcel, $importCtllFicExcel, $em);
        
        // $timestampCreationExcel = $spreadsheet->getProperties()->getCreated();
        // dump($timestampCreationExcel);
        // dd(\DateTimeImmutable::createFromFormat('U', $timestampCreationExcel));
        // if ($timestampCreationExcel) {
        //     $importCtllFicExcel->setDateFichier(\DateTimeImmutable::createFromFormat('U', $timestampCreationExcel));
        // }

        return $importCtllFicExcel;
    }

    private function createImportCtllFicExcel(UploadedFile $FicExcel, string $inputFileName, AuthenticationUtils $authenticationUtils): ImportCtllFicExcel
    {
        // $creation_time = DateTimeImmutable::createFromFormat('U', filectime($inputFileName));
        $import_date = new DateTimeImmutable();
        // $lastUsername = $authenticationUtils->getLastUsername();
        $user = $this->getUser(); // Récupère l'utilisateur connecté
        if ($user) {
            $userName = $user->getUserName(); // Appelle la méthode getUserName() de l'entité User
            // dd($userName); // Affiche le userName pour vérifier
        } else {
            throw $this->createAccessDeniedException('Utilisateur non connecté.');
        }
        $importCtllFicExcel = new ImportCtllFicExcel();
        $importCtllFicExcel
            // ->setDateFichier($creation_time)
            ->setUtilisateurImport($userName)
            ->setFileName($FicExcel->getClientOriginalName())
            ->setDateImport($import_date);
        $this->nomFichierExcel = $FicExcel->getClientOriginalName();
        return $importCtllFicExcel;
    }

    private function processWorksheetRows(Worksheet $activeWorksheet, int $highestRow, ImportCtllFicExcel $importCtllFicExcel, EntityManagerInterface $em): void
    {
        $this->importedRowsCount = 0;
        for ($row = 2; $row <= $highestRow; $row++) {
            $importCtll = new ImportCtll();
            $importCtll = $this->hydrateImportCtll($importCtll, $activeWorksheet, $row, $importCtllFicExcel);
            $em->persist($importCtll);
            $this->importedRowsCount++;
        }
    }

    private function finalizeImport(UploadedFile $FicExcel, ImportCtllFicExcel $importCtllFicExcel, EntityManagerInterface $em): void
    {
        // dd($this->nbDonneesInserees);
        $dateHeureUnique = date('Ymd_His');
        $fileName = 'CTLL_' . $dateHeureUnique . '.xlsx';


        $importCtllFicExcel
            // ->setFileName($fileName)
            ->setNbLignesDataFicExcel($this->importedRowsCount);
        // ->setNbInsertedSusar($this->nbDonneesInserees['nbOfInsertedSusar'])
        // ->setNbInsertedMedic($this->nbDonneesInserees['nbOfInsertedMedic'])
        // ->setNbInsertedEffInd($this->nbDonneesInserees['nbOfInsertedEffInd'])
        // ->setNbInsertedMedHist($this->nbDonneesInserees['nbOfInsertedMedHist'])  
        // ->setNbInsertedIndic($this->nbDonneesInserees['nbOfInsertedIndic'])  
        // ->setNbSusarAttribue($this->nbDonneesInserees['nbSusarAttribue'])  
        // ->setNbMedicAttribue($this->nbDonneesInserees['nbMedicAttribue']);


        $em->persist($importCtllFicExcel);
        $em->flush();
        $this->idImportCtllFicExcel = $importCtllFicExcel->getId();

        $FicExcel->move($this->getParameter('kernel.project_dir') . '/temp/ctll_traites/', $fileName);
    }

    private function hydrateImportCtll(
        ImportCtll $importCtll,
        Worksheet $activeWorksheet,
        int $row,
        ImportCtllFicExcel $importCtllFicExcel
    ): ImportCtll {
        $importCtll->setSafetyReportKey($activeWorksheet->getCell('A' . $row)->getFormattedValue());
        $importCtll->setStudyRegistrationN($activeWorksheet->getCell('B' . $row)->getFormattedValue());
        $importCtll->setSponsorStudyNumber($activeWorksheet->getCell('C' . $row)->getFormattedValue());
        $importCtll->setEVSafetyReportIdentifier($activeWorksheet->getCell('D' . $row)->getFormattedValue());
        $importCtll->setCaseReportNumber($activeWorksheet->getCell('E' . $row)->getFormattedValue());
        $importCtll->setImportCtllFicExcel($importCtllFicExcel);
        $importCtll->setSender($activeWorksheet->getCell('F' . $row)->getFormattedValue());
        $importCtll->setReportType($activeWorksheet->getCell('G' . $row)->getFormattedValue());
        $importCtll->setEVDocumentType($activeWorksheet->getCell('H' . $row)->getFormattedValue());
        $importCtll->setCountry($activeWorksheet->getCell('I' . $row)->getFormattedValue());
        $importCtll->setReceiveDate(new \DateTime($activeWorksheet->getCell('J' . $row)->getFormattedValue()));
        $importCtll->setReceiptDate(new \DateTime($activeWorksheet->getCell('K' . $row)->getFormattedValue()));
        $importCtll->setGatewayDate(new \DateTime($activeWorksheet->getCell('L' . $row)->getFormattedValue()));
        $importCtll->setInitialsHeightWeight($activeWorksheet->getCell('M' . $row)->getFormattedValue());
        $importCtll->setAge($activeWorksheet->getCell('N' . $row)->getFormattedValue());
        $importCtll->setBirthDate($activeWorksheet->getCell('O' . $row)->getFormattedValue());
        $importCtll->setSex($activeWorksheet->getCell('P' . $row)->getFormattedValue());
        $importCtll->setAgeGroup($activeWorksheet->getCell('Q' . $row)->getFormattedValue());
        $importCtll->setPrimarySourceQualification($activeWorksheet->getCell('R' . $row)->getFormattedValue());
        $importCtll->setSerious($activeWorksheet->getCell('S' . $row)->getFormattedValue());
        $importCtll->setSeriousnessDeath($activeWorksheet->getCell('T' . $row)->getFormattedValue());
        $importCtll->setSeriousnessLifethreatening($activeWorksheet->getCell('U' . $row)->getFormattedValue());
        $importCtll->setSeriousnessHospitalisation($activeWorksheet->getCell('V' . $row)->getFormattedValue());
        $importCtll->setSeriousnessDisabling($activeWorksheet->getCell('W' . $row)->getFormattedValue());
        $importCtll->setSeriousnessCongenitalAnomaly($activeWorksheet->getCell('X' . $row)->getFormattedValue());
        $importCtll->setSeriousnessOther($activeWorksheet->getCell('Y' . $row)->getFormattedValue());
        $importCtll->setParentChild($activeWorksheet->getCell('Z' . $row)->getFormattedValue());
        $importCtll->setLiteratureReference($activeWorksheet->getCell('AA' . $row)->getFormattedValue());
        $importCtll->setNumberOfLiteratureReferenceDocuments((int)$activeWorksheet->getCell('AB' . $row)->getFormattedValue());
        $importCtll->setNumberOfDocumentsHeldBySender((int)$activeWorksheet->getCell('AC' . $row)->getFormattedValue());
        $importCtll->setRecodedDrugList($activeWorksheet->getCell('AD' . $row)->getFormattedValue());
        $importCtll->setNumberOfSuspectInteractingDrugs((int)$activeWorksheet->getCell('AE' . $row)->getFormattedValue());
        $importCtll->setSuspectInteractingEnhancedReportedDrugList($activeWorksheet->getCell('AF' . $row)->getFormattedValue());
        $importCtll->setConcomitantNotAdministeredEnhancedReportedDrugList($activeWorksheet->getCell('AG' . $row)->getFormattedValue());
        $importCtll->setIndicationsPTOfTheDrugOfInterestAsReportedInTheICSR($activeWorksheet->getCell('AH' . $row)->getFormattedValue());
        $importCtll->setPositiveRechallengeForSuspectInteractingDrugs($activeWorksheet->getCell('AI' . $row)->getFormattedValue());
        $importCtll->setReactionListPT($activeWorksheet->getCell('AJ' . $row)->getFormattedValue());
        $importCtll->setStructuredMedicalHistory($activeWorksheet->getCell('AK' . $row)->getFormattedValue());
        $importCtll->setNarrativePresent($activeWorksheet->getCell('AL' . $row)->getFormattedValue());
        $importCtll->setNarrativeReportersCommentsAndSendersComments($activeWorksheet->getCell('AM' . $row)->getFormattedValue());
        $importCtll->setICSRForm($activeWorksheet->getCell('AN' . $row)->getFormattedValue());
        $importCtll->setE2B($activeWorksheet->getCell('AO' . $row)->getFormattedValue());
        $importCtll->setSafetyReportID((int)$activeWorksheet->getCell('AP' . $row)->getFormattedValue());
        $importCtll->setSelectICSR((int)$activeWorksheet->getCell('AQ' . $row)->getFormattedValue());
        $importCtll->setCompleteNarrativeReportersCommentsAndSendersComments($activeWorksheet->getCell('AR' . $row)->getFormattedValue());
        $importCtll->setCaseVersion((int)$activeWorksheet->getCell('AS' . $row)->getFormattedValue());

        return $importCtll;
    }

    private function getImportedRowsCount(): int
    {
        return $this->importedRowsCount;
    }
}
