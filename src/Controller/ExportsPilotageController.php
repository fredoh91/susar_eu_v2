<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\SusarEU;
use App\Form\ExportsPilotageTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ExportsPilotageController extends AbstractController
{
    #[Route('/exports_pilotage', name: 'app_exports_pilotage')]
    public function exportPilotage(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ExportsPilotageTypeForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $debutGatewayDate = $data['debutGatewayDate'] ?? null;
            $finGatewayDate = $data['finGatewayDate'] ?? null;

            if (!empty($debutGatewayDate) && !empty($finGatewayDate)) {
                // Les deux valeurs sont présentes et non vides

                $date = new DateTimeImmutable();
                $now = $date->format('Ymd_His');
                $nomFichierExcel = "Liste_Susar_EU_" . $now . ".xlsx";
                $repExport = "./Temp/ExportExcelPilotage/";

                if ($debutGatewayDate <= $finGatewayDate) {

                    $susars = $em->getRepository(SusarEU::class)->findSusarByGatewayDate_exportPilotage($debutGatewayDate, $finGatewayDate);
                    // dump ($susars);

                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();


                    $sheet->setCellValue('A1', 'ID Susar_EU');
                    $sheet->setCellValue('B1', 'N° EUCT');
                    $sheet->setCellValue('C1', 'WorldWide id');
                    $sheet->setCellValue('D1', 'Case version');
                    $sheet->setCellValue('E1', 'Substance');
                    $sheet->setCellValue('F1', 'DMM');
                    $sheet->setCellValue('G1', 'Pôle Court');
                    $sheet->setCellValue('H1', 'Évaluateur');
                    $sheet->setCellValue('I1', 'Niveau Classification');
                    $sheet->setCellValue('J1', 'Gateway date');
                    $sheet->setCellValue('K1', 'Date prévisionnelle');

                    // Largeurs des colonnes
                    $columnWidths = [
                        'A' => 15,  // ID Susar_EU
                        'B' => 60,  // N° EUCT
                        'C' => 65,  // worldWide_id
                        'D' => 14,  // Case version
                        'E' => 80,  // Substance
                        'F' => 15,  // DMM
                        'G' => 35,  // Pôle Court
                        'H' => 25,  // Évaluateur
                        'I' => 25,  // Niveau Classification
                        'J' => 21,  // Gateway date
                        'K' => 21,  // Date prévisionnelle
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

                        $sheet->setCellValue('A' . $row, $susar->getId());
                        $sheet->setCellValue('B' . $row, $susar->getNumEudract());
                        $sheet->getStyle('B' . $row)
                            ->getNumberFormat()
                            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

                        $sheet->setCellValue('C' . $row, $susar->getWorldWideId());
                        $sheet->setCellValue('D' . $row, $susar->getDLPVersion());
                        $sheet->setCellValue('E' . $row, $substances);

                        // saMS/Mone, DMM, Pôle et évaluateur
                        $IntSubs = $susar->getIntervenantSubstanceDMMs();

                        $dmm = '';
                        $poleCourt = '';
                        $evaluateur = '';
                        foreach ($IntSubs as $IntSub) {

                            if ($dmm !== '') {
                                $dmm .= "/";
                            }
                            $dmm .= $IntSub->getDMM();

                            if ($poleCourt !== '') {
                                $poleCourt .= "/";
                            }
                            $poleCourt .= $IntSub->getPoleCourt();

                            if ($evaluateur !== '') {
                                $evaluateur .= "/";
                            }
                            $evaluateur .= $IntSub->getEvaluateur();
                        }
                        $sheet->setCellValue('F' . $row, $dmm);
                        $sheet->setCellValue('G' . $row, $poleCourt);
                        $sheet->setCellValue('H' . $row, $evaluateur);

                        $sheet->setCellValue('I' . $row, $susar->getPriorisation());

                        // Gateway date
                        if ($susar->getGatewayDate() !== null) {
                            // Create a DateTime object from the 'dd/mm/yyyy' format
                            $gatewayDate = $susar->getGatewayDate();

                            if ($gatewayDate) {
                                // Convert the DateTime object to Excel's serial date format
                                $excelgatewayDate = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($gatewayDate);

                                // Set the cell value with the Excel date serial number
                                $sheet->setCellValue('J' . $row, $excelgatewayDate);

                                // Apply the date format to the cell
                                $sheet->getStyle('J' . $row)
                                    ->getNumberFormat()
                                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY);
                            } else {
                                // Handle invalid date formats if necessary
                                $sheet->setCellValue('J' . $row, 'Date Invalide');
                            }
                        }

                        // Date prévisionnelle = gatewayDate + 15 jours
                        $datePrev = null;
                        if ($gatewayDate instanceof \DateTimeInterface) {
                            $datePrev = (clone $gatewayDate)->modify('+15 days');
                            $excelDatePrev = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($datePrev);
                            $sheet->setCellValue('K' . $row, $excelDatePrev);
                            $sheet->getStyle('K' . $row)
                                ->getNumberFormat()
                                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY);
                        } else {
                            $sheet->setCellValue('K' . $row, '');
                        }

                        // Réglage de la hauteur de ligne
                        // $maxValue = max($medicCount, $eiCount, $autreCount, $encoreUnAutreCount);
                        // $maxCount = max($medicCount);
                        $totalHeight = $baseHeight + ($additionalHeight * ($medicCount - 1));
                        $sheet->getRowDimension($row)->setRowHeight($totalHeight);

                        $row++;
                    }

                    ////////////////////////////////////
                    // Mise en forme du fichier Excel //
                    ////////////////////////////////////

                    // On met la première ligne en gris
                    for ($col = 'A'; $col != 'L'; $col++) {
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
                    $sheet->setTitle("Pilotage SUSAR_EU");

                    // On modifie la largeur des colonnes avec auto-dimensionnement pour les colonnes non spécifiées
                    $allColumns = range('A', 'T');
                    foreach ($allColumns as $column) {
                        if (!array_key_exists($column, $columnWidths)) {
                            $sheet->getColumnDimension($column)->setAutoSize(true);
                        }
                    }

                    // Activer le retour à la ligne et l'ajustement automatique de la hauteur pour la colonne E
                    $sheet->getStyle('E1:E' . ($row - 1))
                        ->getAlignment()
                        ->setWrapText(true)
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

                    // Autres colonnes : alignement vertical centré
                    $columns = ['A', 'B', 'C', 'D', 'F', 'G', 'H', 'I', 'J', 'K'];
                    foreach ($columns as $col) {
                        $sheet->getStyle($col . '1:' . $col . ($row - 1))
                            ->getAlignment()
                            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    }

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

                    // $this->addFlash('success', 'Export généré avec succès.');
                    // Return the file as a response
                    return $this->file($repExport . $nomFichierExcel, $nomFichierExcel, ResponseHeaderBag::DISPOSITION_ATTACHMENT);
                } else {
                    // Logique pour traiter les dates
                    // Par exemple, vous pouvez appeler un service pour générer l'export
                    // $this->exportService->generateExport($debutGatewayDate, $finGatewayDate);
                    $this->addFlash('error', 'La date de début doit être antérieure ou égale à la date de fin.');
                }

                // dump($debutGatewayDate, $finGatewayDate);

            } else {
                $this->addFlash('error', 'Merci d\'indiquer une date de début et une date de fin.');
            }
        }

        return $this->render('exports_pilotage/exports_pilotage.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
