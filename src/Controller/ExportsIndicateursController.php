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

final class ExportsIndicateursController extends AbstractController
{
    #[Route('/exports_indicateurs', name: 'app_exports_indicateurs')]
    public function exportIndicateurs(Request $request, EntityManagerInterface $em): Response
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
                $nomFichierExcel = "Indicateurs_Susar_EU_" . $now . ".xlsx";
                $repExport = "./Temp/ExportExcelPilotage/";
                
                if ($debutGatewayDate <= $finGatewayDate) {
                    // dd('Coucou 02');
                    
                    $susars = $em->getRepository(SusarEU::class)->findSusarByGatewayDate_exportIndicateur($debutGatewayDate, $finGatewayDate);

                    // si le repo a renvoyé une erreur SQL pour debug
                    if (is_array($susars) && isset($susars['_sql_error'])) {
                        $err = $susars['_sql_error'];

                        // log (si le service logger est disponible)
                        if ($this->container->has('logger')) {
                            $this->container->get('logger')->error('ExportIndicateurs SQL error', $err);
                        }

                        // message utilisateur et debug
                        $this->addFlash('error', 'Erreur SQL lors de l\'export : ' . ($err['message'] ?? 'voir logs'));
                        dump($err); // enlever en production

                        return $this->render('exports_indicateurs/exports_indicateurs.html.twig', [
                            'form' => $form->createView(),
                        ]);
                    }

                    // dump($susars);

                    if (empty($susars)) {
                        $this->addFlash('info', 'Aucun SUSAR trouvé pour la période sélectionnée.');
                        return $this->render('exports_indicateurs/exports_indicateurs.html.twig', [
                            'form' => $form->createView(),
                        ]);
                    }


                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();


                    $sheet->setCellValue('A1', 'ID Susar_EU');
                    $sheet->setCellValue('B1', 'Case version');
                    $sheet->setCellValue('C1', 'N° EUCT');
                    $sheet->setCellValue('D1', 'Country');
                    $sheet->setCellValue('E1', 'Gateway date');
                    $sheet->setCellValue('F1', 'Date d\'import');
                    $sheet->setCellValue('G1', 'Date prévisionnelle');
                    $sheet->setCellValue('H1', 'Première date d\'eval.');
                    $sheet->setCellValue('I1', 'Dans les délais');
                    $sheet->setCellValue('J1', 'Niveau Classification');
                    $sheet->setCellValue('K1', 'Évalué');
                    $sheet->setCellValue('L1', 'DMM');
                    $sheet->setCellValue('M1', 'Pôle Court');
                    $sheet->setCellValue('N1', 'Évaluateur');
                    $sheet->setCellValue('O1', 'saMS ou Mono');
                    $sheet->setCellValue('P1', 'Substance');
                    $sheet->setCellValue('Q1', 'PT');
                    $sheet->setCellValue('R1', 'Assessment outcome');
                    $sheet->setCellValue('S1', 'Date d\'évaluation');

                    // Largeurs des colonnes
                    $columnWidths = [
                        'A' => 15,  // ID Susar_EU
                        'B' => 60,  // dlpversion
                        'C' => 65,  // num_eudract
                        'D' => 14,  // pays_survenue
                        'E' => 15,  // gateway_date
                        'F' => 15,  // date_import
                        'G' => 15,  // date_previsionnelle
                        'H' => 15,  // premiere_date_eval
                        'I' => 25,  // Dans_les_delais
                        'J' => 21,  // Niveau Classification
                        'K' => 21,  // Evalue
                        'L' => 21,  // DMM
                        'M' => 21,  // pole_court
                        'N' => 21,  // evaluateur
                        'O' => 21,  // Type_saMS_Mono
                        'P' => 21,  // substance_names
                        'Q' => 21,  // EI_pt
                        'R' => 30,  // assessment_outcome
                        'S' => 21,  // date_eval
                    ];

                    foreach ($columnWidths as $column => $width) {
                        $sheet->getColumnDimension($column)->setWidth($width);
                    }


                    $baseHeight = 15; // Hauteur de ligne de base en points
                    $additionalHeight = 15; // Hauteur additionnelle pour substance et EI

                    $row = 2;
// dd( $susars); 

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
                        $sheet->setCellValue('P' . $row, $substances);

                        // PT
                        $eis = $susar->getEffetsIndesirables();
                        $pt = '';
                        $eiCount = 0;
                        foreach ($eis as $ei) {
                            // $substances .= $medic->getProductName() . "\n";
                            if ($pt !== '') {
                                $pt .= "\n";
                            }
                            // $substances .= $medic->getProductName();
                            $pt .= $ei->getReactionListPT();
                            $eiCount++;
                        }
                        $sheet->setCellValue('Q' . $row, $pt);

                        $sheet->setCellValue('A' . $row, $susar->getId());
                        $sheet->setCellValue('B' . $row, $susar->getDLPVersion());
                        $sheet->setCellValue('C' . $row, $susar->getNumEudract());
                        $sheet->getStyle('C' . $row)
                        ->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                        $sheet->setCellValue('D' . $row, $susar->getPaysSurvenue());

                        // Gateway date
                        if ($susar->getGatewayDate() !== null) {
                            // Create a DateTime object from the 'dd/mm/yyyy' format
                            $gatewayDate = $susar->getGatewayDate();

                            if ($gatewayDate) {
                                // Convert the DateTime object to Excel's serial date format
                                $excelgatewayDate = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($gatewayDate);

                                // Set the cell value with the Excel date serial number
                                $sheet->setCellValue('E' . $row, $excelgatewayDate);

                                // Apply the date format to the cell
                                $sheet->getStyle('E' . $row)
                                    ->getNumberFormat()
                                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY);
                            } else {
                                // Handle invalid date formats if necessary
                                $sheet->setCellValue('E' . $row, 'Date Invalide');
                            }
                        }
                        
                        // date_import et date_previsionnelle
                        if ($susar->getGatewayDate() !== null) {
                            $dateImport = $susar->getCreatedAt();

                            if ($dateImport instanceof \DateTimeInterface) {
                                // calculer la date +15 jours sans modifier l'original
                                if ($dateImport instanceof \DateTimeImmutable) {
                                    $datePrev = $dateImport->add(new \DateInterval('P15D'));
                                } else {
                                    $datePrev = (clone $dateImport)->modify('+15 days');
                                }

                                $exceldateImport = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($dateImport);
                                $exceldatePrev   = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($datePrev);

                                $sheet->setCellValue('F' . $row, $exceldateImport);
                                $sheet->getStyle('F' . $row)
                                    ->getNumberFormat()
                                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY);

                                $sheet->setCellValue('G' . $row, $exceldatePrev);
                                $sheet->getStyle('G' . $row)
                                    ->getNumberFormat()
                                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY);
                            } else {
                                $sheet->setCellValue('F' . $row, 'Date Invalide');
                            }
                        }
                        
                        // Calcul de la première date d'évaluation (min date_eval) en mémoire
                        $premiereDateEval = null;
                        foreach ($susar->getSubstancePtEvals() as $spe) {
                            $d = $spe->getDateEval();
                            if ($d instanceof \DateTimeInterface) {
                                if ($premiereDateEval === null || $d < $premiereDateEval) {
                                    $premiereDateEval = $d;
                                }
                            }
                        }

                        // Affichage première date d'éval dans la colonne H
                        if ($premiereDateEval instanceof \DateTimeInterface) {
                            $excelPremiere = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($premiereDateEval);
                            $sheet->setCellValue('H' . $row, $excelPremiere);
                            $sheet->getStyle('H' . $row)
                                ->getNumberFormat()
                                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY);
                        } else {
                            $sheet->setCellValue('H' . $row, 'non-évalué');
                        }

                        // Colonne K : Évalué ?
                        $sheet->setCellValue('K' . $row, ($premiereDateEval instanceof \DateTimeInterface) ? 'évalué' : 'non-évalué');

                        // --- Calcul / normalisation de la date prévisionnelle ($datePrev) si nécessaire ---
                        // $datePrev peut avoir été défini plus haut (createdAt + 15j). On essaye de le réutiliser,
                        // sinon on le calcule depuis createdAt ou gatewayDate (fallback).
                        $datePrev = $datePrev ?? null;
                        if (!($datePrev instanceof \DateTimeInterface)) {
                            $dateImportForPrev = $susar->getCreatedAt() ?? null;
                            if ($dateImportForPrev instanceof \DateTimeInterface) {
                                if ($dateImportForPrev instanceof \DateTimeImmutable) {
                                    $datePrev = $dateImportForPrev->add(new \DateInterval('P15D'));
                                } else {
                                    $datePrev = (clone $dateImportForPrev)->modify('+15 days');
                                }
                            } else {
                                $gatewayForPrev = $susar->getGatewayDate() ?? null;
                                if ($gatewayForPrev instanceof \DateTimeInterface) {
                                    if ($gatewayForPrev instanceof \DateTimeImmutable) {
                                        $datePrev = $gatewayForPrev->add(new \DateInterval('P15D'));
                                    } else {
                                        $datePrev = (clone $gatewayForPrev)->modify('+15 days');
                                    }
                                }
                            }
                        }

                        // Colonne I : Dans les délais ?
                        // Règles :
                        // 1) si première date d'éval <= date prévisionnelle => "Oui"
                        // 2) si première date d'éval > date prévisionnelle => "Non"
                        // 3) si pas de première date d'éval ET date du jour > date prévisionnelle => "Non"
                        // Sinon => vide
                        $delai = '';
                        if ($datePrev instanceof \DateTimeInterface) {
                            // comparer uniquement les dates (sans l'heure)
                            $prevDateOnly = new \DateTimeImmutable($datePrev->format('Y-m-d'));

                            if ($premiereDateEval instanceof \DateTimeInterface) {
                                $premiereDateOnly = new \DateTimeImmutable($premiereDateEval->format('Y-m-d'));
                                $delai = ($premiereDateOnly <= $prevDateOnly) ? 'Oui' : 'Non';
                            } else {
                                $today = new \DateTimeImmutable('today');
                                $delai = ($today > $prevDateOnly) ? 'Non' : '';
                            }
                        } else {
                            $delai = '';
                        }

                        $sheet->setCellValue('I' . $row, $delai);

                        $sheet->setCellValue('J' . $row, $susar->getPriorisation());

                        
                        // saMS/Mono, DMM, Pôle et évaluateur (avec valeur par défaut "non-attribué" si aucune liaison)
                        $IntSubs = $susar->getIntervenantSubstanceDMMs();

                        $dmmParts = [];
                        $poleParts = [];
                        $evalParts = [];
                        $samsParts = [];

                        foreach ($IntSubs as $IntSub) {
                            $dmmParts[]   = $IntSub->getDMM() ?? '';
                            $poleParts[]  = $IntSub->getPoleCourt() ?? '';
                            $evalParts[]  = $IntSub->getEvaluateur() ?? '';

                            // Supporter les deux noms de méthode selon l'entité
                            if (method_exists($IntSub, 'getTypeSaMSMono')) {
                                $samsParts[] = $IntSub->getTypeSaMSMono() ?? '';
                            } else {
                                $samsParts[] = $IntSub->getSamsMono() ?? '';
                            }
                        }

                        // Concaténer en séparant par "/", en supprimant les valeurs vides
                        $dmm       = implode('/', array_filter($dmmParts, function ($v) { return $v !== ''; }));
                        $poleCourt = implode('/', array_filter($poleParts, function ($v) { return $v !== ''; }));
                        $evaluateur= implode('/', array_filter($evalParts, function ($v) { return $v !== ''; }));
                        $samsMono  = implode('/', array_filter($samsParts, function ($v) { return $v !== ''; }));

                        // Si aucune valeur, indiquer "non-attribué"
                        if ($dmm === '') {
                            $dmm = 'non-attribué';
                        }
                        if ($poleCourt === '') {
                            $poleCourt = 'non-attribué';
                        }
                        if ($evaluateur === '') {
                            $evaluateur = 'non-attribué';
                        }
                        if ($samsMono === '') {
                            $samsMono = 'non-attribué';
                        }

                        $sheet->setCellValue('L' . $row, $dmm);
                        $sheet->setCellValue('M' . $row, $poleCourt);
                        $sheet->setCellValue('N' . $row, $evaluateur);
                        $sheet->setCellValue('O' . $row, $samsMono);

                        // --- Colonnes R et S : Assessment outcome (valeurs uniques) et toutes les dates d'évaluation triées (conserver doublons) ---
                        $outcomesMap = [];
                        $evalDates = [];
                        foreach ($susar->getSubstancePtEvals() as $spe) {
                            $ao = $spe->getAssessmentOutcome();
                            if ($ao !== null && trim($ao) !== '') {
                                $outcomesMap[trim($ao)] = true;
                            }

                            $d = $spe->getDateEval();
                            if ($d instanceof \DateTimeInterface) {
                                $evalDates[] = $d;
                            }
                        }

                        // Colonne R : valeurs uniques d'AssessmentOutcome — une valeur par ligne
                        $outcomes = array_keys($outcomesMap);
                        if (!empty($outcomes)) {
                            $sheet->setCellValue('R' . $row, implode("\n", $outcomes));
                            $sheet->getStyle('R' . $row)
                                ->getAlignment()
                                ->setWrapText(true)
                                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                        } else {
                            $sheet->setCellValue('R' . $row, '');
                        }

                        // Colonne S : toutes les dates d'évaluation triées (asc) — une date par ligne, doublons supprimés sur la base de JJ/MM/AAAA
                        if (!empty($evalDates)) {
                            usort($evalDates, function($a, $b) {
                                return $a <=> $b;
                            });

                            // Formater en 'd/m/Y' puis supprimer les doublons tout en préservant l'ordre
                            $formatted = array_map(function($dt) {
                                return $dt->format('d/m/Y'); // jour sur 2 chiffres
                            }, $evalDates);

                            $uniqueFormatted = array_values(array_unique($formatted)); // dédoublonner

                            $sCell = implode("\n", $uniqueFormatted); // un saut de ligne entre chaque date unique
                            $sheet->setCellValue('S' . $row, $sCell);
                            $sheet->getStyle('S' . $row)
                                ->getAlignment()
                                ->setWrapText(true)
                                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                        } else {
                            $sheet->setCellValue('S' . $row, '');
                        }


                        $maxHauteur = max($medicCount, $eiCount);

                        $totalHeight = $baseHeight + ($additionalHeight * ($maxHauteur - 1));
                        $sheet->getRowDimension($row)->setRowHeight($totalHeight);

                        $row++;
                    }
// dd($row);
                    ////////////////////////////////////
                    // Mise en forme du fichier Excel //
                    ////////////////////////////////////

                    // On met la première ligne en gris
                    for ($col = 'A'; $col != 'T'; $col++) {
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
                    $sheet->setTitle("Indicateur SUSAR_EU");

                    // On modifie la largeur des colonnes avec auto-dimensionnement pour les colonnes non spécifiées
                    $allColumns = range('A', 'S');
                    foreach ($allColumns as $column) {
                        if (!array_key_exists($column, $columnWidths)) {
                            $sheet->getColumnDimension($column)->setAutoSize(true);
                        }
                    }

                    // Activer le retour à la ligne et l'ajustement automatique de la hauteur pour la colonne P
                    $sheet->getStyle('P1:P' . ($row - 1))
                        ->getAlignment()
                        ->setWrapText(true)
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

                    // Activer le retour à la ligne et l'ajustement automatique de la hauteur pour la colonne Q
                    $sheet->getStyle('Q1:Q' . ($row - 1))
                        ->getAlignment()
                        ->setWrapText(true)
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

                    // Activer le retour à la ligne et l'ajustement automatique de la hauteur pour la colonne R
                    $sheet->getStyle('R1:R' . ($row - 1))
                        ->getAlignment()
                        ->setWrapText(true)
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

                    // Activer le retour à la ligne et l'ajustement automatique de la hauteur pour la colonne S
                    $sheet->getStyle('S1:S' . ($row - 1))
                        ->getAlignment()
                        ->setWrapText(true)
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

                    // Autres colonnes : alignement vertical centré
                    $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'];
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

                    $this->addFlash('success', sprintf('%d SUSAR exportés avec succès dans le fichier %s.', $row - 2, $nomFichierExcel));
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
        return $this->render('exports_indicateurs/exports_indicateurs.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
