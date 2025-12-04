<?php

namespace App\Repository;

use App\Entity\SusarEU;
use App\Entity\SearchSusarEU;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SusarEU>
 *
 * @method SusarEU|null find($id, $lockMode = null, $lockVersion = null)
 * @method SusarEU|null findOneBy(array $criteria, array $orderBy = null)
 * @method SusarEU[]    findAll()
 * @method SusarEU[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SusarEURepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SusarEU::class);
    }


    public function findWithSubstancePts(int $id): ?SusarEU
    {
        $query = $this->createQueryBuilder('s')
            ->leftJoin('s.substancePts', 'sp')
            ->addSelect('sp')
            ->where('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    public function donneNbIntervenant(int $id): ?SusarEU
    {
        $query = $this->createQueryBuilder('s')
            ->leftJoin('s.IntervenantSubstanceDMMs', 'isd')
            ->addSelect('isd')
            ->where('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * Retourne le nombre d'Intervenantsubstancedmm liés à un Susareu donné.
     *
     * @param int $susareuId
     * @return int
     */
    public function nbIntSubDMM(int $susareuId): int
    {
        $qb = $this->createQueryBuilder('s')
            ->select('COUNT(i.id)')
            ->join('s.intervenantSubstanceDMMs', 'i')
            ->where('s.id = :susareuId')
            ->setParameter('susareuId', $susareuId);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * 
     */
    public function findSusarByMasterId(int $master_id): ?SusarEU
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.master_id = :val')
            ->setParameter('val', $master_id)
            ->getQuery()
            ->getOneOrNullResult();
    }


    /**
     * 
     */
    public function findSusarBySpecificcaseid(string $specificcaseid): ?array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.specificcaseid = :val')
            ->setParameter('val', $specificcaseid)
            ->orderBy('s.DLPVersion', 'ASC')
            ->getQuery()
            ->getResult();
    }



    /**
     * 
     */
    public function findSusarByEVSafetyReportIdentifier(string $EVSafetyReportIdentifier): ?array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.EV_SafetyReportIdentifier = :val')
            ->setParameter('val', $EVSafetyReportIdentifier)
            ->orderBy('s.DLPVersion', 'ASC')
            ->getQuery()
            ->getResult();
    }



    /**
     * 
     */
    public function findSusarByWorldWideId(string $worldWideId): ?array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.worldWide_id = :val')
            ->setParameter('val', $worldWideId)
            ->orderBy('s.DLPVersion', 'DESC')
            ->getQuery()
            ->getResult();
    }
    /**
     * Undocumented function
     *
     * @param array $orderCriteria
     * @return array
     */
    public function findAllOrder(array $orderCriteria = [['field' => 'statusdate', 'direction' => 'ASC']]): array
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->distinct()
            ->andWhere('s.casSusarEuV1 IS NULL');
        foreach ($orderCriteria as $criteria) {
            $field = $criteria['field'];
            $direction = $criteria['direction'] ?? 'ASC'; // Default to 'ASC' if not specified
            $queryBuilder->addOrderBy('s.' . $field, $direction);
        }
        // Ajout de la pagination
        // $queryBuilder->setFirstResult(($page - 1) * $nbResuPage)
        //     ->setMaxResults($nbResuPage);
        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    /**
     * Vérifie l'existence d'une SubstancePtEval liée pour un SusarEU donné
     * avec des valeurs spécifiques de substance et pt.
     *
     * @param int $susarEuId
     * @param string $substance
     * @param string $pt
     * @return bool
     */
    public function hasLinkedSubstancePtEval(int $susarEuId, string $substance, string $pt): bool
    {
        // version 1
        // $qb = $this->createQueryBuilder('se')
        //     ->select('COUNT(spe.id)')
        //     ->join('se.substancePts', 'sp')
        //     ->join('sp.substancePtEvals', 'spe')
        //     ->where('se.id = :susarEuId')
        //     ->andWhere('sp.active_substance_high_level = :substance')
        //     ->andWhere('sp.reactionmeddrapt = :pt')
        //     ->setParameter('susarEuId', $susarEuId)
        //     ->setParameter('substance', $substance)
        //     ->setParameter('pt', $pt);

        // version 2
        $qb = $this->createQueryBuilder('se')
            ->select('COUNT(spe.id)')
            ->join('se.substancePtEvals', 'spe')
            ->join('spe.substancePts', 'sp')
            ->where('se.id = :susarEuId')
            ->andWhere('sp.active_substance_high_level = :substance')
            ->andWhere('sp.reactionmeddrapt = :pt')
            ->setParameter('susarEuId', $susarEuId)
            ->setParameter('substance', $substance)
            ->setParameter('pt', $pt);

        $count = $qb->getQuery()->getSingleScalarResult();

        // dump($qb->getQuery()->getSQL());
        return $count > 0;
    }


    /**
     * Vérifie l'existence d'un EV_SafetyReportIdentifier dans la tableSusarEU.
     *
     * @param string $EV_SafetyReportIdentifier
     * @return bool
     */

    public function existeEV_SafetyReportIdentifier(string $EV_SafetyReportIdentifier): bool
    {
        $result = $this->createQueryBuilder('s')
            ->andWhere('s.EV_SafetyReportIdentifier = :val')
            ->setParameter('val', $EV_SafetyReportIdentifier)
            ->getQuery()
            ->getOneOrNullResult();

        return $result !== null;
    }



    public function countSusarByGatewayDateLastNDays(int $nbJour): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
        WITH RECURSIVE DateRange AS (
            SELECT DATE_SUB(CURDATE(), INTERVAL :nbJour DAY) AS date
            UNION ALL
            SELECT DATE_ADD(date, INTERVAL 1 DAY)
            FROM DateRange
            WHERE date < CURDATE()
        )
        SELECT
            DATE_FORMAT(dr.date, '%d/%m/%Y') AS formatted_gateway_date,
            COUNT(se.id) AS NbSusar
        FROM
            DateRange dr
        LEFT JOIN
            susar_eu se ON DATE(dr.date) = DATE(se.gateway_date)
        GROUP BY
            dr.date
        ORDER BY
            dr.date ASC
    ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('nbJour', $nbJour);
        $result = $stmt->executeQuery();
        return $result->fetchAllAssociative();
    }



    public function countSusarByGatewayDatePerWeekLastNWeeks(int $nbWeeks): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
        WITH RECURSIVE WeekRange AS (
            SELECT
                DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE()) - 1) DAY) AS week_end,
                DATE_SUB(DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE()) - 1) DAY), INTERVAL 6 DAY) AS week_start
            UNION ALL
            SELECT
                DATE_SUB(week_end, INTERVAL 7 DAY),
                DATE_SUB(week_start, INTERVAL 7 DAY)
            FROM
                WeekRange
            WHERE
                week_start > DATE_SUB(CURDATE(), INTERVAL :nbWeeks WEEK)
        )
        SELECT
            DATE_FORMAT(wr.week_start, '%d/%m/%Y') AS start_of_week,
            DATE_FORMAT(wr.week_end, '%d/%m/%Y') AS end_of_week,
            COUNT(se.id) AS NbSusar
        FROM
            WeekRange wr
        LEFT JOIN
            susar_eu se ON DATE(se.gateway_date) BETWEEN DATE(wr.week_start) AND DATE(wr.week_end)
        GROUP BY
            wr.week_start, wr.week_end
        ORDER BY
            wr.week_start ASC
    ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('nbWeeks', $nbWeeks);
        $result = $stmt->executeQuery();
        return $result->fetchAllAssociative();
    }




    public function countSusarByGatewayRgpMonth(int $idInterSub): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
        SELECT
            DATE_FORMAT(se.gateway_date, '%Y-%m') AS month,
            COUNT(*) AS nbSUSARs
        FROM
            susar_eu_v2.intervenant_substance_dmm isd
        LEFT JOIN
            susar_eu_v2.intervenant_substance_dmm_susar_eu isdse ON isdse.intervenant_substance_dmm_id = isd.id
        LEFT JOIN
            susar_eu_v2.susar_eu se ON se.id = isdse.susar_eu_id
        WHERE
            isd.id = :idInterSub
        GROUP BY
            DATE_FORMAT(se.gateway_date, '%Y-%m')
        ORDER BY
            month ASC;
    ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('idInterSub', $idInterSub);
        $result = $stmt->executeQuery();
        return $result->fetchAllAssociative();
    }





    public function findSusarByGatewayDate_exportPilotage($debutGatewayDate, $finGatewayDate): ?array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.GatewayDate >= :dgd')
            ->setParameter('dgd', $debutGatewayDate)
            ->andWhere('s.GatewayDate <= :fgd')
            ->setParameter('fgd', $finGatewayDate->modify('+1 day'))
            ->andWhere('s.dateEvaluation IS NULL')
            ->andWhere('s.priorisation IN (:prios)')
            ->setParameter('prios', ['Niveau 2a', 'Niveau 2b', 'Niveau 2c'])
            // ->andWhere('s.EV_SafetyReportIdentifier = :val')
            // ->setParameter('val', $EVSafetyReportIdentifier)
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function findSusarByGatewayDate_exportIndicateur($debutGatewayDate, $finGatewayDate): ?array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.GatewayDate >= :dgd')
            ->setParameter('dgd', $debutGatewayDate)
            ->andWhere('s.GatewayDate <= :fgd')
            ->setParameter('fgd', $finGatewayDate->modify('+1 day'))
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function findSusarByGatewayDate_exportIndicateur_SQL($debutGatewayDate, $finGatewayDate): ?array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
        SELECT s.id,
            s.dlpversion ,
            s.num_eudract ,
            s.pays_survenue ,
            DATE_FORMAT(s.gateway_date, '%d/%m/%Y') AS gateway_date,
            DATE_FORMAT(s.created_at, '%d/%m/%Y') AS date_import,
            DATE_FORMAT(DATE_ADD(s.created_at, INTERVAL 15 DAY), '%d/%m/%Y') AS date_previsionnelle,
            IFNULL(
                (
                    SELECT DATE_FORMAT(MIN(spe.date_eval), '%d/%m/%Y')
                    FROM substance_pt_eval spe
                    LEFT JOIN substance_pt_eval_susar_eu spese
                        ON spese.substance_pt_eval_id = spe.id
                    WHERE spese.susar_eu_id = s.id
                ),
                'Pas_eval'
            ) AS premiere_date_eval,
            CASE
                WHEN (
                    SELECT MIN(spe.date_eval)
                    FROM substance_pt_eval spe
                    LEFT JOIN substance_pt_eval_susar_eu spese
                        ON spese.substance_pt_eval_id = spe.id
                    WHERE spese.susar_eu_id = s.id
                ) IS NULL THEN 'N/A'
                WHEN (
                    SELECT MIN(spe.date_eval)
                    FROM substance_pt_eval spe
                    LEFT JOIN substance_pt_eval_susar_eu spese
                        ON spese.substance_pt_eval_id = spe.id
                    WHERE spese.susar_eu_id = s.id
                ) <= DATE_ADD(s.created_at, INTERVAL 15 DAY) THEN 'Oui'
                ELSE 'Non'
            END AS Dans_les_delais,
            s.priorisation ,
            CASE
                WHEN EXISTS (
                    SELECT 1
                    FROM substance_pt_eval spe
                    LEFT JOIN substance_pt_eval_susar_eu spese
                        ON spese.substance_pt_eval_id = spe.id
                    WHERE spese.susar_eu_id = s.id
                ) THEN 'VRAI'
                ELSE 'FAUX'
            END AS Evalue,
            (
                SELECT 
                    CASE 
                        WHEN COUNT(isd.dmm) = 0 THEN 'non-attribué'
                        ELSE GROUP_CONCAT(isd.dmm SEPARATOR '/')
                        END AS dmm_concat_2
                FROM intervenant_substance_dmm_susar_eu isdse 
                LEFT JOIN intervenant_substance_dmm isd 
                    ON isd.id = isdse.intervenant_substance_dmm_id 
                    AND isd.dmm IS NOT NULL
                WHERE isdse.susar_eu_id = s.id
            ) AS DMM,
            (
                SELECT 
                    CASE 
                        WHEN COUNT(isd.pole_court) = 0 THEN 'non-attribué'
                        ELSE GROUP_CONCAT(isd.pole_court SEPARATOR '/')
                    END AS pole_court_concat_2
                FROM intervenant_substance_dmm_susar_eu isdse 
                LEFT JOIN intervenant_substance_dmm isd 
                    ON isd.id = isdse.intervenant_substance_dmm_id 
                    AND isd.pole_court IS NOT NULL
                WHERE isdse.susar_eu_id = s.id
            ) AS pole_court,
            (
                SELECT 
                    CASE 
                        WHEN COUNT(isd.evaluateur) = 0 THEN 'non-attribué'
                        ELSE GROUP_CONCAT(isd.evaluateur SEPARATOR '/')
                    END AS evaluateur_concat_2
                FROM intervenant_substance_dmm_susar_eu isdse 
                LEFT JOIN intervenant_substance_dmm isd 
                    ON isd.id = isdse.intervenant_substance_dmm_id 
                    AND isd.evaluateur IS NOT NULL
                WHERE isdse.susar_eu_id = s.id
            ) AS evaluateur,
            (
                SELECT 
                    CASE 
                        WHEN COUNT(isd.type_sa_ms_mono) = 0 THEN 'non-attribué'
                        ELSE GROUP_CONCAT(isd.type_sa_ms_mono SEPARATOR '/')
                    END AS type_sa_ms_mono_concat_2
                FROM intervenant_substance_dmm_susar_eu isdse 
                LEFT JOIN intervenant_substance_dmm isd 
                    ON isd.id = isdse.intervenant_substance_dmm_id 
                    AND isd.type_sa_ms_mono IS NOT NULL
                WHERE isdse.susar_eu_id = s.id
            ) AS Type_saMS_Mono,
            (
                SELECT GROUP_CONCAT(DISTINCT m.substancename SEPARATOR '/')
                FROM medicaments m
                WHERE m.susar_id = s.id
                and m.productcharacterization = 'Suspect'
            ) AS substance_names,
            (
                SELECT GROUP_CONCAT(DISTINCT ei.reaction_list_pt SEPARATOR '/')
                FROM effets_indesirables ei 
                WHERE ei.susar_id = s.id
            ) AS EI_pt ,
            (
                SELECT 
                CASE 
                    WHEN COUNT(spe.assessment_outcome) = 0 THEN NULL
                    ELSE GROUP_CONCAT(distinct spe.assessment_outcome SEPARATOR '/')
                END AS ass_out_concat
                FROM substance_pt_eval spe
                LEFT JOIN substance_pt_eval_susar_eu spese
                    ON spese.substance_pt_eval_id = spe.id
                WHERE spese.susar_eu_id = s.id
            ) AS assessment_outcome	,
            (
                SELECT 
                    CASE 
                        WHEN COUNT(spe.date_eval ) = 0 THEN NULL
                        ELSE GROUP_CONCAT(distinct DATE_FORMAT(spe.date_eval, '%d/%m/%Y') SEPARATOR ',')
                    END AS date_eval_concat
                FROM substance_pt_eval spe
                LEFT JOIN substance_pt_eval_susar_eu spese
                    ON spese.substance_pt_eval_id = spe.id
                WHERE spese.susar_eu_id = s.id
            ) AS date_eval
        FROM susar_eu_v2.susar_eu s
        WHERE s.gateway_date BETWEEN :dgd AND :fgd
        ";

        $dgd = $debutGatewayDate->format('Y-m-d 00:00:00');
        $fgd = $finGatewayDate->format('Y-m-d 23:59:59');
        
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('dgd', $dgd);
            $stmt->bindValue('fgd', $fgd);
            $result = $stmt->executeQuery();
            return $result->fetchAllAssociative();
        } catch (\Doctrine\DBAL\Exception $e) {
            $error = [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'sql' => $sql,
                'params' => ['dgd' => $dgd, 'fgd' => $fgd],
            ];
            if ($e->getPrevious()) {
                $error['previous'] = $e->getPrevious()->getMessage();
            }
            // DEBUG temporaire : retourne les infos d'erreur pour inspection
            return ['_sql_error' => $error];
            // en prod, logger l'erreur puis relancer : throw new \RuntimeException($e->getMessage(), 0, $e);
        } catch (\Throwable $e) {
            return ['_sql_error' => ['message' => $e->getMessage(), 'sql' => $sql, 'params' => ['dgd' => $dgd, 'fgd' => $fgd]]];
        }
    }









    // public function countSusarByGatewayDateLastNDays(int $nbJour): array
    // {
    //     $qb = $this->createQueryBuilder('se')
    //         ->select('se.GatewayDate, COUNT(se.id) AS NbSusar')
    //         ->where('se.GatewayDate >= :dateLimit')
    //         // ->setParameter('dateLimit', (new \DateTime('-90 days'))->setTime(0, 0, 0))
    //         // ->setParameter('dateLimit', (new \DateTime('-30 days'))->setTime(0, 0, 0))
    //         ->setParameter('dateLimit', (new \DateTime("-$nbJour days"))->setTime(0, 0, 0))

    //         ->groupBy('se.GatewayDate')
    //         ->orderBy('se.GatewayDate', 'ASC');

    //     $results = $qb->getQuery()->getResult();

    //     // Formatage des dates en PHP
    //     $formatted = [];
    //     foreach ($results as $row) {
    //         $date = $row['GatewayDate'];
    //         $formatted[] = [
    //             'formatted_gateway_date' => $date ? $date->format('d/m/Y') : null,
    //             'NbSusar' => $row['NbSusar'],
    //         ];
    //     }
    //     return $formatted;
    // }

    //    /**
    //     * @return SusarEU[] Returns an array of SusarEU objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?SusarEU
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
