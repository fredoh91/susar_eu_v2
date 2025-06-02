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
        $lstSusarGateway = $repositorySusarEu->countSusarByGatewayDateLastNDays(30);

        // Préparation des données pour Chart.js
        $labels = [];
        $data = [];
        foreach ($lstSusarGateway as $row) {
            $labels[] = $row['formatted_gateway_date'];
            $data[] = $row['NbSusar'];
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Nb SUSAR par date Gateway',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'data' => $data,
                    // 'tension' => 0.4,
                ],
            ],
        ]);
        $chart->setOptions([
            'maintainAspectRatio' => false,
        ]);

        return $this->render('bilan_imports_ctll/affiche_bilan_import_ctll.html.twig', [
            'bilanImportsCtll' => $bilanImportsCtll,
            // 'lstSusarGateway' => $lstSusarGateway,
            'chart' => $chart,
        ]);
    }
}
