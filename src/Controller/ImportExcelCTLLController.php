<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\ImportCtll;
use App\Form\UploadExcelCTLLType;
use App\Entity\ImportCttlFicExcel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


final class ImportExcelCTLLController extends AbstractController{
    #[Route('/import_excel_ctll', name: 'app_import_excel_ctll')]
    public function upload_excel_ctll(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $em, AuthenticationUtils $authenticationUtils): Response
    {
        $form = $this->createForm(UploadExcelCTLLType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $FicExcel */
            $FicExcel = $form->get('FicExcel')->getData();

            if ($FicExcel) {
                $inputFileName = './Temp/CTLL.xlsx';
                $inputFileName = $FicExcel->getRealPath();
                // dump($inputFileName);
                $spreadsheet = IOFactory::load($inputFileName);
                $activeWorksheet = $spreadsheet->getActiveSheet();
                $highestRow = $activeWorksheet->getHighestRow();
                $creation_time = DateTimeImmutable::createFromFormat('U', filectime($inputFileName));
                $import_date = new DateTimeImmutable();
                $lastUsername = $authenticationUtils->getLastUsername();

                $importCttlFicExcel = new ImportCttlFicExcel();
                $importCttlFicExcel->setDateFichier($creation_time);
                $importCttlFicExcel->setUtilisateurImport($lastUsername);
                $importCttlFicExcel->setFileName($FicExcel->getClientOriginalName());
                $importCttlFicExcel->setDateImport($import_date);
                for ($row = 2; $row <= $highestRow; $row++) {
                    $SafetyReportKey = $activeWorksheet->getCell('A' . $row)->getFormattedValue();
                    $StudyRegistrationN = $activeWorksheet->getCell('B' . $row)->getFormattedValue();
                    $SponsorStudyNumber = $activeWorksheet->getCell('C' . $row)->getFormattedValue();
                    $EV_SafetyReportIdentifier = $activeWorksheet->getCell('D' . $row)->getFormattedValue();
                    $CaseReportNumber = $activeWorksheet->getCell('E' . $row)->getFormattedValue();

                    $importCtll = new ImportCtll();
                    $importCtll->setSafetyReportKey($SafetyReportKey);
                    $importCtll->setStudyRegistrationN($StudyRegistrationN); 
                    $importCtll->setSponsorStudyNumber($SponsorStudyNumber);
                    $importCtll->setEVSafetyReportIdentifier($EV_SafetyReportIdentifier);
                    $importCtll->setCaseReportNumber($CaseReportNumber);
                    $importCtll->setImportCttlFicExcel($importCttlFicExcel);

                    $em->persist($importCtll);
                }

                $dateHeureUnique = date('Ymd_His');
                $fileName = 'CTLL_' . $dateHeureUnique . '.xlsx';
                $importCttlFicExcel->setFileName($fileName);
                $em->persist($importCttlFicExcel);
                $em->flush();

                $FicExcel->move($this->getParameter('kernel.project_dir') . '/temp/ctll_traites/', $fileName);
            }
        }
        return $this->render('import_excel_ctll/upload_excel_ctll.html.twig', [
            // 'controller_name' => 'ImportExcelCTLLController',
            'form' => $form->createView()
        ]);
    }
}
