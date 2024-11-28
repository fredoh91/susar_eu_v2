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

class ExportExcelController extends AbstractController
{

    #[Route('/export_excel_susar_eu_liste', name: 'app_export_excel_susar_eu_liste', methods: ['POST'])]
    public function exportSusarEU(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // $searchSusarEU = json_decode($request->get('searchSusarEU'), true);
        // $triSearchSusarEU = json_decode($request->get('triSearchSusarEU'), true);
        // $searchSusarEU = json_decode($request->request->get('search_susar_eu'), true);
//         $searchSusarEU = json_decode($request->request->get('search_susar_eu'), true);

//         // dump($request->request->get('search_susar_eu'));
        // dump($request->request->get_headers()); 
        // dd($request);
//         // dd(json_decode($request->getContent(), true));
// // dd( $searchSusarEU, $triSearchSusarEU);
// dd( $searchSusarEU);


$searchSusarEu = $request->request->get('search_susar_eu');

// Vérifier si les données sont présentes
if ($searchSusarEu === null) {
    throw new \Exception('La clé "search_susar_eu" est absente dans la requête POST.');
}

// Afficher ou traiter les données
dd($searchSusarEu); // Affiche dans la barre de débogage ou dans le log
// Exemple d'accès à une sous-clé
$casArchive = $searchSusarEu['casArchive'] ?? null;
dump($casArchive); // Affiche la valeur de "casArchive"



$searchSusarEU = json_decode($request->request->get('search_susar_eu'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    // Handle JSON decoding error
    throw new \Exception('Error decoding JSON: ' . json_last_error_msg());
}

// Access data from the decoded array
$casArchive = $searchSusarEU['casArchive'];
// $evaluateurChoice = $searchSusarEU['evaluateurChoice'];
dd($casArchive);



        // Récupérer les données
        if ($searchSusarEU) {
            $susars = $entityManager->getRepository(SusarEU::class)
                ->findBySearchSusarEuListe($searchSusarEU, $triSearchSusarEU);
        } else {
            $susars = $entityManager->getRepository(SusarEU::class)
                ->findAllOrder($triSearchSusarEU);
        }

        // Créer le fichier Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Définir les en-têtes (à adapter selon vos colonnes)
        $headers = ['ID', 'Colonne1', 'Colonne2', 'Colonne3']; // Adaptez selon vos besoins
        foreach ($headers as $key => $header) {
            $sheet->setCellValue(chr(65 + $key) . '1', $header);
        }

        // Remplir les données
        $row = 2;
        foreach ($susars as $susar) {
            $sheet->setCellValue('A' . $row, $susar->getId());
            $sheet->setCellValue('B' . $row, $susar->getColonne1());
            $sheet->setCellValue('C' . $row, $susar->getColonne2());
            $sheet->setCellValue('D' . $row, $susar->getColonne3());
            $row++;
        }

        // Créer le fichier Excel
        $writer = new Xlsx($spreadsheet);
        $fileName = 'export_susar_eu_' . date('Y-m-d_His') . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        // Retourner le fichier
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_ATTACHMENT);
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
