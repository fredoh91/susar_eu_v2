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
