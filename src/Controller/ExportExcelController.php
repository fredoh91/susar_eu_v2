<?php

namespace App\Controller;

use DateTime;
use DateTimeImmutable;
use App\Entity\SusarEU;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(new Expression('is_granted("ROLE_DMM_EVAL") or is_granted("ROLE_SURV_PILOTEVEC")'))]
class ExportExcelController extends AbstractController
{

    // #[Route('/export_excel_susar_eu_liste', name: 'app_export_excel_susar_eu_liste', methods: ['POST'])]
    #[Route('/export_excel_susar_eu_liste', name: 'app_export_excel_susar_eu_liste')]
    public function exportSusarEU(Request $request, EntityManagerInterface $entityManager): Response 
    {

        $date = new DateTimeImmutable();
        $now = $date->format('Ymd_His');
        $nomFichierExcel= "Liste_Susar_EU_" . $now . ".xlsx";
        $repExport = "./Temp/ExportExcelListeSusarEU/";


        $session = $request->getSession();
        $searchSusarEU = $session->get('search_susar_eu');
        $triSearchSusarEU = $session->get('tri_search_susar_eu');

        
        // dd($searchSusarEU);

        // // Retrieve the parameters from the query string
        // $searchSusarEU = json_decode($request->query->get('searchSusarEU'), true);
        // $triSearchSusarEU = json_decode($request->query->get('triSearchSusarEU'), true);

        // // Check for JSON decoding errors
        // if (json_last_error() !== JSON_ERROR_NONE) {
        //     throw new \Exception('Error decoding JSON: ' . json_last_error_msg());
        // }

        // Retrieve the data
        if ($searchSusarEU) {
            $susars = $entityManager->getRepository(SusarEU::class)
                ->findBySearchSusarEuListe($searchSusarEU, $triSearchSusarEU);
        } else {
            $susars = $entityManager->getRepository(SusarEU::class)
                ->findAllOrder($triSearchSusarEU);
        }


        // dd(count($susars));
        // Create the Excel file
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Define the headers
        // $headers = ['ID', 'Master ID', 'Case ID', 'Specific Case ID', 'DLP Version', 'WorldWide ID'];
        // foreach ($headers as $key => $header) {
        //     $sheet->setCellValue(chr(65 + $key) . '1', $header);
        // }
        $sheet->setCellValue('A1', 'ID Susar_EU');
        $sheet->setCellValue('B1', 'WorldWide id');
        $sheet->setCellValue('C1', 'Num. BNPV');
        $sheet->setCellValue('D1', 'FU BNPV');
        $sheet->setCellValue('E1', 'N° EudraCT');
        $sheet->setCellValue('F1', 'Sender');
        $sheet->setCellValue('G1', 'Pays survenue');
        $sheet->setCellValue('H1', 'Status date');
        $sheet->setCellValue('I1', 'Creation date');
        $sheet->setCellValue('J1', 'Substance');
        $sheet->setCellValue('K1', 'Effet(s) indésirable(s)');
        $sheet->setCellValue('L1', 'Gravité');
        $sheet->setCellValue('M1', 'Niveau Classification');
        $sheet->setCellValue('N1', 'Évalué');
        $sheet->setCellValue('O1', 'Type_saMS_Mono');
        $sheet->setCellValue('P1', 'DMM');
        $sheet->setCellValue('Q1', 'Pôle Court');
        $sheet->setCellValue('R1', 'Évaluateur');
        $sheet->setCellValue('S1', 'Assessment outcome');
        $sheet->setCellValue('T1', 'Commentaire évaluation');

        // Largeurs des colonnes
        $columnWidths = [
            'A' => 13,  // ID Susar_EU
            'B' => 40,  // WorldWide id
            'C' => 17,  // Num. BNPV
            'D' => 11,  // FU BNPV
            'E' => 20,  // N° EudraCT
            'F' => 30,  // Sender
            'G' => 18,  // Pays survenue
            'H' => 16,  // Status date
            'I' => 16,  // Creation date
            'J' => 50,  // Substance
            'K' => 50,  // Effet(s) indésirable(s)
            'L' => 55,  // Gravité
            'M' => 22,  // Niveau Classification
            'N' => 10,  // Évalué
            'O' => 24,  // Type_saMS_Mono
            'P' => 15,  // DMM
            'Q' => 20,  // Pôle Court
            'R' => 25,  // Évaluateur
            'S' => 85,  // Assessment outcome
            'T' => 70,  // Commentaire évaluation
        ];

        foreach ($columnWidths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        $baseHeight = 15; // Hauteur de ligne de base en points
        $additionalHeight = 15; // Hauteur additionnelle pour substance et EI

        $row = 2;

        foreach ($susars as $susar) {

            // Substances
            $medics = $susar->getMedicament();
            $substances = '';
            $medicCount = 0;
            foreach ($medics as $medic) {
                if ($medic->getProductcharacterization() === 'Suspect' || $medic->getProductcharacterization() === 'Interacting') {
                    // $substances .= $medic->getProductName() . "\n";
                    if ($substances !== '') {
                        $substances .= "\n";
                    }
                    // $substances .= $medic->getProductName();
                    $substances .= $medic->getSubstancename();
                    $medicCount++;
                }
            }

            // Effets indésirables
            $EIs = $susar->getEffetsIndesirables();
            $PT = '';
            $eiCount = 0;
            foreach ($EIs as $EI) {
                // $PT .= $EI->getReactionmeddrapt() . ' (' . $EI->getCodereactionmeddrapt() . ')' . "\n";
                if ($PT !== '') {
                    $PT .= "\n";
                }
                $PT .= $EI->getReactionmeddrapt() . ' (' . $EI->getCodereactionmeddrapt() . ')';
                $eiCount++;
            }


            $sheet->setCellValue('A' . $row, $susar->getId());
            $sheet->setCellValue('B' . $row, $susar->getWorldWideId());
            // $sheet->setCellValue('B' . $row, $susar->getMasterId());
            // $sheet->setCellValue('C' . $row, $susar->getCaseid());
            $sheet->setCellValue('C' . $row, $susar->getSpecificcaseid());
            $sheet->setCellValue('D' . $row, $susar->getDLPVersion());
            $sheet->setCellValue('E' . $row, $susar->getNumEudract());
            $sheet->setCellValue('F' . $row, '');
            $sheet->setCellValue('G' . $row, $susar->getPaysSurvenue());

            // Status date
            if ($susar->getStatusdate() !== null) {
                // Create a DateTime object from the 'dd/mm/yyyy' format
                $statusDate = $susar->getStatusdate();
    
                if ($statusDate) {
                    // Convert the DateTime object to Excel's serial date format
                    $excelStatusDate = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($statusDate);
    
                    // Set the cell value with the Excel date serial number
                    $sheet->setCellValue('H' . $row, $excelStatusDate);
    
                    // Apply the date format to the cell
                    $sheet->getStyle('H' . $row)
                        ->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY);
                } else {
                    // Handle invalid date formats if necessary
                    $sheet->setCellValue('H' . $row, 'Date Invalide');
                }
            }


            // Creation date
            if ($susar->getStatusdate() !== null) {
                // Create a DateTime object from the 'dd/mm/yyyy' format
                $creationDate = $susar->getCreationdate();
    
                if ($creationDate) {
                    // Convert the DateTime object to Excel's serial date format
                    $excelCreationDate = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($creationDate);
    
                    // Set the cell value with the Excel date serial number
                    $sheet->setCellValue('I' . $row, $excelCreationDate);
    
                    // Apply the date format to the cell
                    $sheet->getStyle('I' . $row)
                        ->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY);
                } else {
                    // Handle invalid date formats if necessary
                    $sheet->setCellValue('I' . $row, 'Date Invalide');
                }
            }

            $sheet->setCellValue('J' . $row, $substances);
            $sheet->setCellValue('K' . $row, $PT);
            
            
            // $sheet->setCellValue('L' . $row, $susar->getSeriousnessCriteria());
            $seriousnessCriteria = str_replace('<BR>', "\n", $susar->getSeriousnessCriteria() ?? '');
            $seriousnessCriteria = trim($seriousnessCriteria);
            $countSeriousCrit = $seriousnessCriteria ? substr_count($seriousnessCriteria, "\n") + 1 : 0;
            
            $sheet->setCellValue('L' . $row, $seriousnessCriteria);

            $sheet->setCellValue('M' . $row, $susar->getPriorisation());
            

            if ($susar->getDateEvaluation()  !== null) {
                $sheet->setCellValue('N' . $row, 'Oui');
            } else {
                $sheet->setCellValue('N' . $row, 'Non');
            }
            
            // saMS/Mone, DMM, Pôle et évaluateur
            $IntSubs = $susar->getIntervenantSubstanceDMMs();
            $samsMono = '';
            $dmm = '';
            $poleCourt = '';
            $evaluateur = '';
            foreach ($IntSubs as $IntSub) {
                if ($samsMono !== '') {
                    $samsMono .= "/";
                }
                $samsMono .= $IntSub->getTypeSaMSMono() ;

                if ($dmm !== '') {
                    $dmm .= "/";
                }
                $dmm .= $IntSub->getDMM() ;

                if ($poleCourt !== '') {
                    $poleCourt .= "/";
                }
                $poleCourt .= $IntSub->getPoleCourt() ;

                if ($evaluateur !== '') {
                    $evaluateur .= "/";
                }
                $evaluateur .= $IntSub->getEvaluateur() ;
            }
            $sheet->setCellValue('O' . $row, $samsMono);
            $sheet->setCellValue('P' . $row, $dmm);
            $sheet->setCellValue('Q' . $row, $poleCourt);
            $sheet->setCellValue('R' . $row, $evaluateur);
            

            // Évaluations
            $Evals = $susar->getSubstancePtEvals();
            $assessOut = '';
            $evalComm = '';
            foreach ($Evals as $Eval) {
                if ($assessOut !== '') {
                    $assessOut .= "\n";
                }

                $AS_HL = '';
                $PT = '';
                $subPTs = $Eval->getSubstancePts();

                foreach ($subPTs as $subPT) {   // Substances
                    if ($AS_HL !== '') {
                        $AS_HL .= "-";
                    }
                    $AS_HL .= $subPT->getActiveSubstanceHighLevel();

                    if ($PT !== '') {
                        $PT .= "-";
                    }
                    $PT .= $subPT->getReactionmeddrapt();
                }
                $assessOut .= $Eval->getAssessmentOutcome() . ' (' . 
                                $AS_HL . '/' . 
                                $PT . ')';

                if ($evalComm !== '') {
                    $evalComm .= "\n";
                }
                $evalComm .= $Eval->getComments() ;

                // $eiCount++;
            }
            $sheet->setCellValue('S' . $row, $assessOut);
            $sheet->setCellValue('T' . $row, $evalComm);



            // Réglage de la hauteur de ligne
            // $maxValue = max($medicCount, $eiCount, $autreCount, $encoreUnAutreCount);
            $maxCount = max($medicCount, $eiCount, $countSeriousCrit);
            $totalHeight = $baseHeight + ($additionalHeight * ($maxCount - 1));
            $sheet->getRowDimension($row)->setRowHeight($totalHeight);

            $row++;
        }
        
        
        ////////////////////////////////////
        // Mise en forme du fichier Excel //
        ////////////////////////////////////
        
        // On met la premier ligne en gris
        for($col = 'A'; $col != 'U'; $col++) {
            $sheet->getStyle($col . '1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D6DCE1');
        }
        
        // Ajout du filtre automatique
        $sheet->setAutoFilter(
            $sheet->calculateWorksheetDimension()
        );
        
        // On freeze la ligne de titre de colonne
        $sheet->freezePane('A2');
        
        // On modifie le nom de l'onglet
        $sheet->setTitle("Liste Susar_EU"); 
        
        // On modifie la largeur des colonnes avec auto-dimensionnement pour les colonnes non spécifiées
        $allColumns = range('A', 'T');
        foreach ($allColumns as $column) {
            if (!array_key_exists($column, $columnWidths)) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
        }
        
        // Activer le retour à la ligne et l'ajustement automatique de la hauteur pour les colonnes J, K, L, S et T
        $sheet->getStyle('J1:L' . ($row + count($susars)))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $sheet->getStyle('S1:T' . ($row + count($susars)))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        // On se positionne sur la cellule en haut à gauche
        $sheet->setSelectedCell('A2');

        // Create the Excel writer
        $writer = new Xlsx($spreadsheet);
        
        // Ensure the export directory exists
        if (!is_dir($repExport)) {
            mkdir($repExport, 0777, true);
        }

        // Save the Excel file to the specified directory
        $writer->save($repExport . $nomFichierExcel);

        // Return the file as a response
        return $this->file($repExport . $nomFichierExcel, $nomFichierExcel, ResponseHeaderBag::DISPOSITION_ATTACHMENT);

    }




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
