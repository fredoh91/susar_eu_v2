<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\ImportCtll;
use App\Form\UploadExcelCTLLType;
use App\Entity\ImportCtllFicExcel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\ImportVersSusarEu;

final class ImportExcelCTLLController extends AbstractController{
    private int $importedRowsCount = 0;
    private int $idImportCtllFicExcel = -1;
    private ImportVersSusarEu $importVersSusarEu ;

    public function __construct(ImportVersSusarEu $importVersSusarEu)
    {
        $this->importVersSusarEu = $importVersSusarEu;
    }

    #[Route('/import_excel_ctll', name: 'app_import_excel_ctll')]
    public function upload_excel_ctll(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $em, AuthenticationUtils $authenticationUtils): Response
    {


        $form = $this->createForm(UploadExcelCTLLType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $FicExcel */
            $FicExcel = $form->get('FicExcel')->getData();

            if ($FicExcel) {
                $this->importExcelVersTbImportCtll($FicExcel, $em, $authenticationUtils);
                // TODO: 
                $nbDonneesInserees=$this->importVersSusarEu->importExcelVersTbSusarEu($this->idImportCtllFicExcel, 
                                                                    $em, 
                                                                    $authenticationUtils);

                $nbDonneesInserees['nbOfExcelRow'] = $this->importedRowsCount;
                dump($nbDonneesInserees);
            }
        }
        
        return $this->render('import_excel_ctll/upload_excel_ctll.html.twig', [
            // 'controller_name' => 'ImportExcelCTLLController',
            'form' => $form->createView()
        ]);
    }

    private function importExcelVersTbImportCtll(UploadedFile $FicExcel, EntityManagerInterface $em, AuthenticationUtils $authenticationUtils): void
    {
        $inputFileName = $FicExcel->getRealPath();
        $spreadsheet = IOFactory::load($inputFileName);
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $highestRow = $activeWorksheet->getHighestRow();
        
        $importCtllFicExcel = $this->createImportCtllFicExcel($FicExcel, $inputFileName, $authenticationUtils);
        
        $this->processWorksheetRows($activeWorksheet, $highestRow, $importCtllFicExcel, $em);
        
        $this->finalizeImport($FicExcel, $importCtllFicExcel, $em);
    }

    private function createImportCtllFicExcel(UploadedFile $FicExcel, string $inputFileName, AuthenticationUtils $authenticationUtils): ImportCtllFicExcel
    {
        $creation_time = DateTimeImmutable::createFromFormat('U', filectime($inputFileName));
        $import_date = new DateTimeImmutable();
        $lastUsername = $authenticationUtils->getLastUsername();

        $importCtllFicExcel = new ImportCtllFicExcel();
        $importCtllFicExcel->setDateFichier($creation_time)
            ->setUtilisateurImport($lastUsername)
            ->setFileName($FicExcel->getClientOriginalName())
            ->setDateImport($import_date);

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
        $dateHeureUnique = date('Ymd_His');
        $fileName = 'CTLL_' . $dateHeureUnique . '.xlsx';
        $importCtllFicExcel->setFileName($fileName)
                        ->setNbLignesDataFicExcel($this->importedRowsCount);
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
