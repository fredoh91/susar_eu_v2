<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use App\Service\BilanSusarsService;

final class BilanSusarsController extends AbstractController
{
    #[Route('/bilan_susars', name: 'app_bilan_susars')]
    public function index(Request $request, ChartBuilderInterface $chartBuilder, BilanSusarsService $service): Response
    {
		[$defaultStart, $defaultEnd] = $service->computeDefaultDateRange();
		$startParam = $request->query->get('start');
		$endParam = $request->query->get('end');
		$start = $startParam ? new \DateTimeImmutable($startParam . ' 00:00:00.000') : $defaultStart;
		$end = $endParam ? new \DateTimeImmutable($endParam . ' 23:59:59.999') : $defaultEnd;

		$months = $service->generateMonthKeys($start, $end);

		// Indicateur 1: priorisation
		$rows1 = $service->getCountsByPriorisationPerMonth($start, $end);
        [$chart1Counts, $chart1Pct, $table1Counts, $table1Pct] = $this->buildStackedChartsAndTable($chartBuilder, $months, $rows1, 'priorisation', 'Nombre de susar par mois du niveau de priorisation');

		// Indicateur 2: évalué vs non évalué
		$rows2 = $service->getCountsEvaluatedVsNonPerMonth($start, $end);
        [$chart2Counts, $chart2Pct, $table2Counts, $table2Pct] = $this->buildStackedChartsAndTable($chartBuilder, $months, $rows2, 'status', 'Nombre de susar par mois du statut évalué/non-évalué');

		// Indicateur 3: DMM / Pôle
		$rows3 = $service->getCountsByDmmPolePerMonth($start, $end);
        [$chart3Counts, $chart3Pct, $table3Counts, $table3Pct] = $this->buildStackedChartsAndTable($chartBuilder, $months, $rows3, 'dmm_pole', 'Nombre de susar par mois selon la DMM/Pole');

		// Indicateur 4: Propositions
		$indicator4Rows = $service->getCountsForIndicator4($start, $end);
		$chartsProp1 = $this->buildProposal1Charts($chartBuilder, $months, $indicator4Rows);
		$chartsProp2 = $this->buildProposal2Charts($chartBuilder, $months, $indicator4Rows);
        
        return $this->render('bilan_susars/bilan_susars.html.twig', [
			'filters' => [
				'start' => $start->format('Y-m-d'),
				'end' => $end->format('Y-m-d'),
			],
			'charts' => [
                [
                    'id' => 'chart1',
                    'title' => 'Nombre de susar par mois du niveau de priorisation',
                    'chart_counts' => $chart1Counts,
                    'chart_percent' => $chart1Pct,
                    'table_counts' => $table1Counts,
                    'table_percent' => $table1Pct,
                ],
				[
					'id' => 'chart2',
                    'title' => 'Nombre de susar par mois du statut évalué/non-évalué',
					'chart_counts' => $chart2Counts,
					'chart_percent' => $chart2Pct,
                    'table_counts' => $table2Counts,
                    'table_percent' => $table2Pct,
				],
				[
					'id' => 'chart3',
                    'title' => 'Nombre de susar par mois selon la DMM/Pole',
					'chart_counts' => $chart3Counts,
					'chart_percent' => $chart3Pct,
                    'table_counts' => $table3Counts,
                    'table_percent' => $table3Pct,
				],
			],
			'proposal1_charts' => $chartsProp1,
			'proposal2_charts' => $chartsProp2,
		]);
    }

	private function buildProposal1Charts(ChartBuilderInterface $chartBuilder, array $months, array $rows): array
	{
		// Data structure: [dmm_pole][month][prio_status] = effectif
		$matrix = [];
		$dmm_poles = [];
		$prio_statuses = [];
		foreach ($rows as $r) {
			$dmm_pole = $r['dmm_pole'];
			$prio_status = $r['priorisation'] . ' - ' . $r['status'];
			$matrix[$dmm_pole][$r['ym']][$prio_status] = (int)$r['effectif'];
			$dmm_poles[$dmm_pole] = true;
			$prio_statuses[$prio_status] = true;
		}
		$dmm_poles = array_keys($dmm_poles);
		sort($dmm_poles);
		$prio_statuses = array_keys($prio_statuses);
		sort($prio_statuses);

		$palette = $this->getColorPalette(count($prio_statuses));
		$charts = [];

		foreach($dmm_poles as $dmm_pole) {
			$datasets = [];
			$idx = 0;
			foreach($prio_statuses as $prio_status) {
				$data = [];
				foreach($months as $month) {
					$data[] = $matrix[$dmm_pole][$month][$prio_status] ?? 0;
				}
				$color = $palette[$idx % count($palette)];
				$datasets[] = [
					'label' => $prio_status,
					'backgroundColor' => $this->rgbaFromRgb($color, 0.7),
					'borderColor' => $color,
					'data' => $data,
					'stack' => 'stack1',
				];
				$idx++;
			}

			$chart = $chartBuilder->createChart(Chart::TYPE_BAR);
			$chart->setData(['labels' => $months, 'datasets' => $datasets]);
			$chart->setOptions([
				'responsive' => true,
				'maintainAspectRatio' => false,
				'plugins' => [
					'title' => ['display' => true, 'text' => $dmm_pole],
					'tooltip' => ['mode' => 'index', 'intersect' => false],
				],
				'scales' => ['x' => ['stacked' => true], 'y' => ['stacked' => true, 'beginAtZero' => true]],
			]);
			$charts[] = $chart;
		}
		return $charts;
	}

	private function buildProposal2Charts(ChartBuilderInterface $chartBuilder, array $months, array $rows): array
	{
		// Data structure: [month][dmm_pole][prio_status] = effectif
		$matrix = [];
		$dmm_poles = [];
		$prio_statuses = [];
		foreach ($rows as $r) {
			$dmm_pole = $r['dmm_pole'];
			$prio_status = $r['priorisation'] . ' - ' . $r['status'];
			$matrix[$r['ym']][$dmm_pole][$prio_status] = (int)$r['effectif'];
			$dmm_poles[$dmm_pole] = true;
			$prio_statuses[$prio_status] = true;
		}
		$dmm_poles = array_keys($dmm_poles);
		sort($dmm_poles);
		$prio_statuses = array_keys($prio_statuses);
		sort($prio_statuses);

		$palette = $this->getColorPalette(count($prio_statuses));
		$charts = [];

		foreach($months as $month) {
			$datasets = [];
			$idx = 0;
			foreach($prio_statuses as $prio_status) {
				$data = [];
				foreach($dmm_poles as $dmm_pole) {
					$data[] = $matrix[$month][$dmm_pole][$prio_status] ?? 0;
				}
				$color = $palette[$idx % count($palette)];
				$datasets[] = [
					'label' => $prio_status,
					'backgroundColor' => $this->rgbaFromRgb($color, 0.7),
					'borderColor' => $color,
					'data' => $data,
					'stack' => 'stack1',
				];
				$idx++;
			}

			$chart = $chartBuilder->createChart(Chart::TYPE_BAR);
			$chart->setData(['labels' => $dmm_poles, 'datasets' => $datasets]);
			$chart->setOptions([
				'responsive' => true,
				'maintainAspectRatio' => false,
				'plugins' => [
					'title' => ['display' => true, 'text' => 'Répartition pour le mois ' . $month],
					'tooltip' => ['mode' => 'index', 'intersect' => false],
				],
				'scales' => ['x' => ['stacked' => true], 'y' => ['stacked' => true, 'beginAtZero' => true]],
			]);
			$charts[] = $chart;
		}
		return $charts;
	}

	/**
	 * @param array<int,string> $months
	 * @param array<int,array<string,mixed>> $rows expects keys: ym, groupKey, effectif
	 */
    private function buildStackedChartsAndTable(ChartBuilderInterface $chartBuilder, array $months, array $rows, string $groupKey, string $label): array
	{
		// Collect distinct groups
		$groups = [];
		foreach ($rows as $r) {
			$g = $r[$groupKey] ?? 'NR';
			$groups[$g] = true;
		}
		$groups = array_keys($groups);

		// Initialize matrix: group -> month -> 0
		$matrix = [];
		foreach ($groups as $g) {
			$matrix[$g] = array_fill_keys($months, 0);
		}

		foreach ($rows as $r) {
			$ym = $r['ym'];
			$g = $r[$groupKey] ?? 'NR';
			$matrix[$g][$ym] = (int) $r['effectif'];
		}

		// Build datasets with distinct colors
		$palette = $this->getColorPalette();
		$datasets = [];
		$idx = 0;
		foreach ($matrix as $g => $series) {
			$color = $palette[$idx % count($palette)];
			$bgColor = $this->rgbaFromRgb($color, 0.6);
			$datasets[] = [
				'label' => (string) $g,
				'backgroundColor' => $bgColor,
				'borderColor' => $color,
				'borderWidth' => 1,
				'data' => array_values($series),
				'stack' => 'stack1',
			];
			$idx++;
		}

		$chartCounts = $chartBuilder->createChart(Chart::TYPE_BAR);
		$chartCounts->setData([
			'labels' => $months,
			'datasets' => $datasets,
		]);
		$chartCounts->setOptions([
			'responsive' => true,
			'maintainAspectRatio' => false,
			'plugins' => [
				'tooltip' => [
					'mode' => 'index',
					'intersect' => false
				],
				'title' => ['display' => true, 'text' => $label],
				'legend' => [
					'labels' => [
						'usePointStyle' => true,
						'pointStyle' => 'rect',
						'boxWidth' => 12,
						'boxHeight' => 12,
					],
				],
			],
			'scales' => [
				'x' => ['stacked' => true],
				'y' => ['stacked' => true, 'beginAtZero' => true],
			],
		]);

		// Build percent datasets
		$totals = array_fill_keys($months, 0);
		foreach ($matrix as $series) {
			foreach ($months as $m) {
				$totals[$m] += $series[$m];
			}
		}
		$datasetsPct = [];
		$idx = 0;
		foreach ($matrix as $g => $series) {
			$color = $palette[$idx % count($palette)];
			$bgColor = $this->rgbaFromRgb($color, 0.6);
			$vals = [];
			foreach ($months as $m) {
				$den = $totals[$m] ?: 0;
				$vals[] = $den === 0 ? 0 : round(($series[$m] / $den) * 100, 1);
			}
			$datasetsPct[] = [
				'label' => (string) $g,
				'backgroundColor' => $bgColor,
				'borderColor' => $color,
				'borderWidth' => 1,
				'data' => $vals,
				'stack' => 'stack1',
			];
			$idx++;
		}

		$chartPct = $chartBuilder->createChart(Chart::TYPE_BAR);
		$chartPct->setData([
			'labels' => $months,
			'datasets' => $datasetsPct,
		]);
		$chartPct->setOptions([
			'responsive' => true,
			'maintainAspectRatio' => false,
			'plugins' => [
				'tooltip' => [
					'mode' => 'index',
					'intersect' => false,
				],
				'title' => ['display' => true, 'text' => $label . ' — %'],
				'legend' => [
					'labels' => [
						'usePointStyle' => true,
						'pointStyle' => 'rect',
						'boxWidth' => 12,
						'boxHeight' => 12,
					],
				],
			],
			'scales' => [
				'x' => ['stacked' => true],
				'y' => ['stacked' => true, 'beginAtZero' => true, 'max' => 100],
			],
		]);

        // Table (counts)
        $totals = array_fill_keys($months, 0);
        foreach ($matrix as $series) {
            foreach ($months as $m) {
                $totals[$m] += $series[$m];
            }
        }

        $tableCounts = [
            'headers' => array_merge(['Groupe'], $months, ['Total']),
            'rows' => [],
        ];
        foreach ($matrix as $g => $series) {
            $row = [ (string) $g ];
            $sum = 0;
            foreach ($months as $m) {
                $val = (int) $series[$m];
                $row[] = $val;
                $sum += $val;
            }
            $row[] = $sum;
            $tableCounts['rows'][] = $row;
        }
        $totRow = ['Total'];
        $grand = 0;
        foreach ($months as $m) {
            $totRow[] = $totals[$m];
            $grand += $totals[$m];
        }
        $totRow[] = $grand;
        $tableCounts['rows'][] = $totRow;

        // Table (percent)
        $tablePercent = [
            'headers' => array_merge(['Groupe'], $months, ['Total']),
            'rows' => [],
        ];
        foreach ($datasetsPct as $idxRow => $ds) {
            $g = $ds['label'];
            $row = [ (string) $g ];
            $sum = 0.0;
            foreach ($ds['data'] as $val) {
                $row[] = $val;
                $sum += (float) $val;
            }
            // Total en % n'a pas vraiment de sens par ligne, on laisse vide ou somme des % (<=100)
            $row[] = round($sum, 1);
            $tablePercent['rows'][] = $row;
        }
        $totPctRow = ['Total'];
        foreach ($months as $m) {
            $totPctRow[] = 100.0;
        }
        $totPctRow[] = 100.0;
        $tablePercent['rows'][] = $totPctRow;

        return [$chartCounts, $chartPct, $tableCounts, $tablePercent];
	}

	private function getColorPalette(int $num = 0): array
	{
		$base = [
			'rgb(255, 99, 132)',
			'rgb(54, 162, 235)',
			'rgb(255, 206, 86)',
			'rgb(75, 192, 192)',
			'rgb(153, 102, 255)',
			'rgb(255, 159, 64)',
			'rgb(99, 132, 255)',
			'rgb(162, 235, 54)',
			'rgb(206, 86, 255)',
			'rgb(192, 75, 192)',
		];
		if ($num <= count($base)) {
			return $base;
		}
		// Generate more colors if needed
		$colors = $base;
		for($i = count($base); $i < $num; $i++) {
			$colors[] = 'rgb(' . rand(0,255) . ',' . rand(0,255) . ',' . rand(0,255) . ')';
		}
		return $colors;
	}

	private function rgbaFromRgb(string $rgb, float $alpha): string
	{
		// expects format: rgb(r, g, b)
		if (!str_starts_with($rgb, 'rgb(')) {
			return $rgb; // fallback
		}
		$inside = trim(substr($rgb, 4, -1));
		return 'rgba(' . $inside . ', ' . rtrim(sprintf('%.2f', $alpha), '0.') . ')';
    }
}
