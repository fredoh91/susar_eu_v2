<?php

namespace App\Controller;

use App\Entity\SusarEU;
use App\Entity\ImportCtllFicExcel;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

final class BilanImportsCtllController extends AbstractController
{
    #[Route('/bilan_imports_ctll', name: 'app_bilan_imports_ctll')]
    #[IsGranted(new Expression('is_granted("ROLE_DMFR_REF") or is_granted("ROLE_SURV_PILOTEVEC")'))]
    public function index(
        ManagerRegistry $doctrine,
        ChartBuilderInterface $chartBuilder
    ): Response
    {
        $entityManager = $doctrine->getManager();
        
        $repository = $entityManager->getRepository(ImportCtllFicExcel::class);
        $bilanImportsCtll = $repository->findAllOrderDateImportDesc();
        
        $repositorySusarEu = $entityManager->getRepository(SusarEU::class);
        $lstSusarGateway_day = $repositorySusarEu->countSusarByGatewayDateLastNDays(30);

        $lstSusarGateway_week = $repositorySusarEu->countSusarByGatewayDatePerWeekLastNWeeks(10);

        // Préparation des données pour Chart.js
        $labels = [];
        $data = [];
        foreach ($lstSusarGateway_day as $row) {
            $labels[] = $row['formatted_gateway_date'];
            $data[] = $row['NbSusar'];
        }

        $chart_day = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart_day->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Nb SUSAR par date Gateway',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'data' => $data,
                    'tension' => 0.2,
                ],
            ],
        ]);
        $chart_day->setOptions([
            'maintainAspectRatio' => false,
            'scales' => [
                'x' => [
                    'ticks' => [
                        'maxRotation' => 45, // angle maximum
                        'minRotation' => 45, // angle minimum
                        // 'autoSkip' => false, // optionnel : pour ne pas masquer d'étiquettes
                    ],
                ],
            ],
        ]);


        
        $labels = [];
        $data = [];
        foreach ($lstSusarGateway_week as $row) {
            $labels[] = $row['start_of_week'] . ' - ' . $row['end_of_week'];
            $data[] = $row['NbSusar'];
        }

        $chart_week = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart_week->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Nb SUSAR par semaine',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'data' => $data,
                    'tension' => 0.2,
                ],
            ],
        ]);
        $chart_week->setOptions([
            'maintainAspectRatio' => false,
            'scales' => [
                'x' => [
                    'ticks' => [
                        'maxRotation' => 45,
                        'minRotation' => 45,
                    ],
                ],
            ],
        ]);

        return $this->render('bilan_imports_ctll/affiche_bilan_import_ctll.html.twig', [
            'bilanImportsCtll' => $bilanImportsCtll,
            // 'lstSusarGateway' => $lstSusarGateway,
            'chart_day' => $chart_day,
            'chart_week' => $chart_week,
        ]);
    }
}
