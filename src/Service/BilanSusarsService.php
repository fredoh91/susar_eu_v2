<?php

namespace App\Service;

use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;

class BilanSusarsService
{
	private Connection $connection;

	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	public function computeDefaultDateRange(): array
	{
		$now = new DateTimeImmutable('now');
		$prevMonth = $now->modify('first day of last month');
		$start = $prevMonth->modify('-1 year')->setTime(0, 0, 0, 0);
		$end = $prevMonth->modify('last day of this month')->setTime(23, 59, 59, 999000);
		return [$start, $end];
	}

	public function generateMonthKeys(DateTimeImmutable $start, DateTimeImmutable $end): array
	{
		$startMonth = $start->modify('first day of this month')->setTime(0, 0, 0, 0);
		$endMonth = $end->modify('first day of this month')->setTime(0, 0, 0, 0);
		$period = new DatePeriod($startMonth, new DateInterval('P1M'), $endMonth->modify('+1 month'));
		$labels = [];
		foreach ($period as $dt) {
			$labels[] = $dt->format('Y-m');
		}
		return $labels;
	}

	/**
	 * Indicateur 1: effectifs par mois et priorisation (stacked)
	 */
	public function getCountsByPriorisationPerMonth(DateTimeImmutable $start, DateTimeImmutable $end): array
	{
		$sql = <<<SQL
		SELECT DATE_FORMAT(se.gateway_date, '%Y-%m') AS ym,
		       COALESCE(se.priorisation, 'Non renseigné') AS priorisation,
		       COUNT(se.id) AS effectif
		FROM susar_eu_v2.susar_eu se
		WHERE se.gateway_date BETWEEN :start AND :end
		GROUP BY ym, priorisation
		ORDER BY ym ASC, priorisation ASC
		SQL;

		$rows = $this->connection->fetchAllAssociative($sql, [
			'start' => $start->format('Y-m-d H:i:s.u'),
			'end' => $end->format('Y-m-d H:i:s.u'),
		]);

		return $rows;
	}

	/**
	 * Sous-requêtes évalué/non-évalué
	 */
	private function getSubqueryEvaluated(): string
	{
		return <<<SQL
		SELECT DISTINCT se.id
		FROM susar_eu_v2.susar_eu se
		LEFT JOIN susar_eu_v2.substance_pt_eval_susar_eu spese ON spese.susar_eu_id = se.id
		LEFT JOIN susar_eu_v2.substance_pt_eval spe ON se.id = spe.id
		WHERE se.gateway_date BETWEEN :start AND :end
		  AND (spe.comments != 'Screened without action automatic (export CTST)' AND spe.comments != 'Assessed without action automatic')
		SQL;
	}

	private function getSubqueryNonEvaluated(): string
	{
		return <<<SQL
		SELECT DISTINCT se.id
		FROM susar_eu_v2.susar_eu se
		LEFT JOIN susar_eu_v2.substance_pt_eval_susar_eu spese ON spese.susar_eu_id = se.id
		LEFT JOIN susar_eu_v2.substance_pt_eval spe ON se.id = spe.id
		WHERE se.gateway_date BETWEEN :start AND :end
		  AND (spe.comments = 'Screened without action automatic (export CTST)' OR spe.comments = 'Assessed without action automatic')
		UNION
		SELECT DISTINCT se.id
		FROM susar_eu_v2.susar_eu se
		LEFT JOIN susar_eu_v2.substance_pt_eval_susar_eu spese ON spese.susar_eu_id = se.id
		LEFT JOIN susar_eu_v2.substance_pt_eval spe ON se.id = spe.id
		WHERE se.gateway_date BETWEEN :start AND :end
		  AND spe.id IS NULL
		SQL;
	}

	/**
	 * Indicateur 2: évalués vs non-évalués par mois (stacked)
	 */
	public function getCountsEvaluatedVsNonPerMonth(DateTimeImmutable $start, DateTimeImmutable $end): array
	{
		$subEval = $this->getSubqueryEvaluated();
		$subNon = $this->getSubqueryNonEvaluated();

		$sql = <<<SQL
		SELECT DATE_FORMAT(se.gateway_date, '%Y-%m') AS ym,
		       status,
		       COUNT(se.id) AS effectif
		FROM susar_eu_v2.susar_eu se
		JOIN (
		   SELECT id, 'Évalué' AS status FROM ({$subEval}) t1
		   UNION ALL
		   SELECT id, 'Non évalué' AS status FROM ({$subNon}) t2
		) s ON s.id = se.id
		WHERE se.gateway_date BETWEEN :start AND :end
		GROUP BY ym, status
		ORDER BY ym ASC, status ASC
		SQL;

		$rows = $this->connection->fetchAllAssociative($sql, [
			'start' => $start->format('Y-m-d H:i:s.u'),
			'end' => $end->format('Y-m-d H:i:s.u'),
		]);

		return $rows;
	}

	/**
	 * Indicateur 3: DMM / Pôle par mois (stacked)
	 */
	public function getCountsByDmmPolePerMonth(DateTimeImmutable $start, DateTimeImmutable $end): array
	{
		$sql = <<<SQL
		SELECT DATE_FORMAT(se.gateway_date, '%Y-%m') AS ym,
		       CONCAT(COALESCE(isd.dmm, 'NR'), '/', COALESCE(isd.pole_court, 'NR')) AS dmm_pole,
		       COUNT(se.id) AS effectif
		FROM susar_eu_v2.susar_eu se
		LEFT JOIN susar_eu_v2.intervenant_substance_dmm_susar_eu isdse ON isdse.susar_eu_id = se.id
		LEFT JOIN susar_eu_v2.intervenant_substance_dmm isd ON isdse.intervenant_substance_dmm_id = isd.id
		WHERE se.gateway_date BETWEEN :start AND :end
		GROUP BY ym, dmm_pole
		ORDER BY ym ASC, dmm_pole ASC
		SQL;

		$rows = $this->connection->fetchAllAssociative($sql, [
			'start' => $start->format('Y-m-d H:i:s.u'),
			'end' => $end->format('Y-m-d H:i:s.u'),
		]);

		return $rows;
	}
	/**
	 * Indicateur 4: DMM / Pôle, priorisation, status par mois
	 */
	public function getCountsForIndicator4(DateTimeImmutable $start, DateTimeImmutable $end): array
	{
		$subEval = $this->getSubqueryEvaluated();
		$subNon = $this->getSubqueryNonEvaluated();

		$sql = <<<SQL
		SELECT 
			DATE_FORMAT(se.gateway_date, '%Y-%m') AS ym,
			CONCAT(COALESCE(isd.dmm, 'NR'), '/', COALESCE(isd.pole_court, 'NR')) AS dmm_pole,
			COALESCE(se.priorisation, 'Non renseigné') AS priorisation,
			s.status,
			COUNT(se.id) AS effectif
		FROM susar_eu_v2.susar_eu se
		JOIN (
			SELECT id, 'Évalué' AS status FROM ({$subEval}) t1
			UNION ALL
			SELECT id, 'Non évalué' AS status FROM ({$subNon}) t2
		) s ON s.id = se.id
		LEFT JOIN susar_eu_v2.intervenant_substance_dmm_susar_eu isdse ON isdse.susar_eu_id = se.id
		LEFT JOIN susar_eu_v2.intervenant_substance_dmm isd ON isdse.intervenant_substance_dmm_id = isd.id
		WHERE se.gateway_date BETWEEN :start AND :end
		GROUP BY ym, dmm_pole, priorisation, status
		ORDER BY ym ASC, dmm_pole ASC, priorisation ASC, status ASC
		SQL;

		$rows = $this->connection->fetchAllAssociative($sql, [
			'start' => $start->format('Y-m-d H:i:s.u'),
			'end' => $end->format('Y-m-d H:i:s.u'),
		]);

		return $rows;
	}
}


