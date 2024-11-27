<?php

namespace App\Controller;

use DateTime;
// use App\Entity\Susar;
use App\Entity\SusarEU;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;




class ExportExcelPilotageController extends AbstractController
{
    #[Route('/export_excel_pilotage', name: 'app_export_excel_pilotage')]
    public function exportExcel( ManagerRegistry $doctrine): Response
    {



        $date = new DateTimeImmutable();
        $now = $date->format('Ymd_His');
        $nomFichierExcel= "Indic_Susar_Jarde_" . $now . ".xlsx";
        $repExport = "./Temp/ExportExcelPilotage/";
        // $spreadsheet = new Spreadsheet();
        // $activeWorksheet = $spreadsheet->getActiveSheet();

        // $entityManager = $doctrine->getManager();
        // $repo = $entityManager->getRepository(Susar::class);
        // $LstSusarsPilotage=$repo->TousSusarPilotage();

        // $activeWorksheet->setCellValue('A1', 'idSUSAR');
        // $activeWorksheet->setCellValue('B1', 'Date d\'import');
        // $activeWorksheet->setCellValue('C1', 'Date prévisionnelle');
        // $activeWorksheet->setCellValue('D1', 'Case Version');
        // $activeWorksheet->setCellValue('E1', 'Num_EUDRACT');
        // $activeWorksheet->setCellValue('F1', 'Produit');
        // $activeWorksheet->setCellValue('G1', 'DCI');
        // $activeWorksheet->setCellValue('H1', 'DMM_pole_court');
        // $activeWorksheet->setCellValue('I1', 'Mesure/Action');
        // $activeWorksheet->setCellValue('J1', 'Commentaire');
        // $activeWorksheet->setCellValue('K1', 'Util. eval.');
        // $activeWorksheet->setCellValue('L1', 'Date eval.');
        // $activeWorksheet->setCellValue('M1', 'Susar évalué');
        // $activeWorksheet->setCellValue('N1', 'Pays survenue');
        // $activeWorksheet->setCellValue('O1', 'survenue en France');
        
        // $iCpt = 1;
        // foreach ($LstSusarsPilotage as $Susar) {
        //     $iCpt++;
        //     $activeWorksheet->setCellValue('A' . $iCpt, $Susar["idSUSAR"]);

        //     $Date_import_timestamp = gmmktime(  0,
        //                                         0,
        //                                         0,
        //                                         substr($Susar["Date_import"], 3, 2),
        //                                         substr($Susar["Date_import"], 0, 2),
        //                                         substr($Susar["Date_import"], 6, 4));
        //     $spreadsheet->getActiveSheet()->setCellValue('B' . $iCpt, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($Date_import_timestamp));
        //     $spreadsheet->getActiveSheet()->getStyle('B' . $iCpt)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY);

        //     $Date_prev_timestamp = gmmktime(0,
        //                                     0,
        //                                     0,
        //                                     substr($Susar["Date_prev"], 3, 2),
        //                                     substr($Susar["Date_prev"], 0, 2),
        //                                     substr($Susar["Date_prev"], 6, 4));
        //     $spreadsheet->getActiveSheet()->setCellValue('C' . $iCpt, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($Date_prev_timestamp));
        //     $spreadsheet->getActiveSheet()->getStyle('C' . $iCpt)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY);

        //     $activeWorksheet->setCellValue('D' . $iCpt, $Susar["DLPVersion"]);
        //     $activeWorksheet->setCellValue('E' . $iCpt, $Susar["num_eudract"]);
        //     $activeWorksheet->setCellValue('F' . $iCpt, $Susar["productName"]);
        //     $activeWorksheet->setCellValue('G' . $iCpt, $Susar["substanceName"]);
        //     $activeWorksheet->setCellValue('H' . $iCpt, $Susar["DMM_pole_court"]);
        //     $activeWorksheet->setCellValue('I' . $iCpt, $Susar["Libelle"]);
        //     $activeWorksheet->setCellValue('J' . $iCpt, $Susar["Commentaire"]);
        //     $activeWorksheet->setCellValue('K' . $iCpt, $Susar["utilisateurEvaluation"]);

        //     if ($Susar["Date_eval"]!=null) {
        //         $Date_eval_timestamp = gmmktime(0,
        //                                         0,
        //                                         0,
        //                                         substr($Susar["Date_eval"], 3, 2),
        //                                         substr($Susar["Date_eval"], 0, 2),
        //                                         substr($Susar["Date_eval"], 6, 4));
        //         $spreadsheet->getActiveSheet()->setCellValue('L' . $iCpt, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($Date_eval_timestamp));
        //         $spreadsheet->getActiveSheet()->getStyle('L' . $iCpt)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY);
        //     }

        //     $activeWorksheet->setCellValue('M' . $iCpt, $Susar["Susar_evalue"]);
        //     $activeWorksheet->setCellValue('N' . $iCpt, $Susar["pays_survenue"]);
        //     $activeWorksheet->setCellValue('O' . $iCpt, $Susar["survenue_france"]);
        // }
        
        // // On met la premier ligne en gris
        // for($col = 'A'; $col != 'P'; $col++) {
        //     $activeWorksheet->getStyle($col . '1')->getFill()
        //                                     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //                                     ->getStartColor()->setARGB('D6DCE1');
        // }
        
        // // Ajout du filtre automatique
        // $activeWorksheet->setAutoFilter(
        //     $activeWorksheet->calculateWorksheetDimension()
        // );
        
        // // On freeze la ligne de titre de colonne
        // $activeWorksheet->freezePane('A2');
        
        // // On modifie le nom de l'onglet
        // $activeWorksheet->setTitle("UneLigne"); 
        
        // // On modifie la largeur des colonnes
        // foreach ($activeWorksheet->getColumnIterator() as $column) {
        //     switch($column->getColumnIndex())
        //     {
        //         case "E":
        //             $activeWorksheet->getColumnDimension('F')->setWidth(80, 'mm');
        //             break;
        //         case "F":
        //             $activeWorksheet->getColumnDimension('G')->setWidth(80, 'mm');
        //             break;
        //         case "I":
        //             $activeWorksheet->getColumnDimension('J')->setWidth(80, 'mm');
        //             break;
        //         default:
        //             $activeWorksheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        //     };
        // }

        // $writer = new Xlsx($spreadsheet);
        // $writer->save($repExport . $nomFichierExcel);

        return $this->render('export_excel_susar_eu_liste/index.html.twig', [
            'controller_name' => 'ExportExcelPilotageController',
            'nomFichierExcel' => $repExport . $nomFichierExcel,
        ]);
    }
}
