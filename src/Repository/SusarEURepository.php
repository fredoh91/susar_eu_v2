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
