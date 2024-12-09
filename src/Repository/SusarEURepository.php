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
    // public function findWithMedicamentsWithIntervenantSubstanceDMM(int $id): ?SusarEU
    // {
    //     $query = $this->createQueryBuilder('s')
    //         ->leftJoin('s.Medicament', 'med')
    //         ->addSelect('med')
    //         ->where('s.id = :id')
    //         ->setParameter('id', $id)
    //         ->getQuery();

    //     return $query->getOneOrNullResult();
    // }
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
    public function findSusarByMasterId(int $master_id): ?SusarEU
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.master_id = :val')
            ->setParameter('val', $master_id)
            ->getQuery()
            ->getOneOrNullResult();
    }



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
     * retourne la liste des SUSARs recherchés par un formulaire du type SearchListeEvalSusarType
     *
     * @param SearchListeEvalSusar $search
     * @return Susar|null
     */
    // public function findBySearchSusarEuListe(SearchSusarEU $search,
    // array $orderCriteria = [['field' => 'statusdate', 'direction' => 'ASC']],
    // int $page = 1, 
    // int $nbResuPage = 50
    // ): ?array
    public function findBySearchSusarEuListe(SearchSusarEU $search,
                                            array $orderCriteria = [['field' => 'statusdate', 'direction' => 'ASC']]
                                            ): ?array
    {

        $query = $this->createQueryBuilder('s')
                    ->distinct();

        // $query->join('s.intervenantSubstanceDMMs', 'isd');
        $query->leftJoin('s.intervenantSubstanceDMMs', 'isd');        // Je mets un left join pour les cas où il n'y a pas d'intervenantSubstanceDMMs et ainsi pouvoir retrouver les susars de ce type
        $query->join('s.Medicament', 'med');
        $query->join('s.EffetsIndesirables', 'ei');
        $query->leftJoin('s.substancePtEvals', 'spe');
        
        if ($search->getSpecificcaseid()) {
            $query = $query
            ->andWhere('s.specificcaseid = :sci')
            ->setParameter('sci', $search->getSpecificcaseid());
        }

        if ($search->getIdSusar()) {
            $query = $query
                ->andWhere('s.id = :ids')
                ->setParameter('ids', $search->getIdSusar());
        }

        if ($search->getdmmPoleChoice()) {
            $DmmPole = $search->getdmmPoleChoice();

            if (strpos($DmmPole, "/") !== false) {
                $ele = explode("/", $DmmPole);
                $Dmm = $ele[0];
                $Pole = $ele[1];
                $query = $query
                    ->andWhere($query->expr()->eq('isd.pole_court', ':pole_court'))
                    ->setParameter('pole_court', $Pole);
            } else {
                // Aucun tiret trouvé dans la chaîne.
            }
        }
        
        if ($search->getevaluateurChoice()) {
            $Eval = $search->getevaluateurChoice();
            if ($Eval === "_non attribué_") {
                $query = $query
                    ->andWhere($query->expr()->isNull('isd.evaluateur'));
            } else {
                $query = $query
                    ->andWhere($query->expr()->eq('isd.evaluateur', ':eval'))
                    ->setParameter('eval', $Eval);
            }
        }
        
        if ($search->getTypeSaMSMono()) {
            // dd($search->getTypeSaMSMono());
            $query = $query
                ->andWhere($query->expr()->eq('isd.type_saMS_Mono', ':typeSaMSMono'))
                ->setParameter('typeSaMSMono', $search->getTypeSaMSMono());
            // $Eval = $search->getTypeSaMSMono();
            // if ($Eval === "_non attribué_") {
            //     $query = $query
            //         ->andWhere($query->expr()->isNull('isd.evaluateur'));
            // } else {
            //     $query = $query
            //         ->andWhere($query->expr()->eq('isd.evaluateur', ':eval'))
            //         ->setParameter('eval', $Eval);
            // }

        }

        if ($search->getDebutDateImport()) {
            $query = $query
                ->andWhere('s.dateImport >= :ddi')
                ->setParameter('ddi', $search->getDebutDateImport());
        }

        if ($search->getFinDateImport()) {
            $query = $query
                ->andWhere('s.dateImport <= :fdi')
                ->setParameter('fdi', $search->getFinDateImport()->modify('+1 day'));
        }
        if ($search->getsubstanceName()) {
            $sub = $search->getsubstanceName();
            $query = $query
                ->andWhere($query->expr()->like('med.substancename', ':sn'))
                ->setParameter('sn', '%' . $sub . '%');
        }
        if ($search->geteffetIndesirable()) {
            $EI = $search->geteffetIndesirable();
            $query = $query
                ->andWhere($query->expr()->like('ei.reactionmeddrapt', ':ei'))
                ->setParameter('ei', '%' . $EI . '%');
        }
        if ($search->getnarratif()) {
            $narr = $search->getnarratif();
            $query = $query
                ->andWhere($query->expr()->like('s.narratif', ':nar'))
                ->setParameter('nar', '%' . $narr . '%');
        }

        $orExpressions = [];
        $parameters = [];
        
        if ($search->getNiveau1()) {
            $orExpressions[] = $query->expr()->eq('s.priorisation', ':n1');
            $parameters['n1'] = 'Niveau 1';
        }
        
        if ($search->getNiveau2a()) {
            $orExpressions[] = $query->expr()->eq('s.priorisation', ':n2a');
            $parameters['n2a'] = 'Niveau 2a';
        }
        
        if ($search->getNiveau2b()) {
            $orExpressions[] = $query->expr()->eq('s.priorisation', ':n2b');
            $parameters['n2b'] = 'Niveau 2b';
        }
        
        if ($search->getNiveau2c()) {
            $orExpressions[] = $query->expr()->eq('s.priorisation', ':n2c');
            $parameters['n2c'] = 'Niveau 2c';
        }
        
        if (!empty($orExpressions)) {
            $query->andWhere($query->expr()->orX(...$orExpressions));
            
            foreach ($parameters as $key => $value) {
                $query->setParameter($key, $value);
            }
        } else {
            // Si aucune option n'est sélectionnée, exclure tous les niveaux
            $query->andWhere(
                $query->expr()->notIn('s.priorisation', [':n1', ':n2a', ':n2b', ':n2c'])
            )
            ->setParameter('n1', 'Niveau 1')
            ->setParameter('n2a', 'Niveau 2a')
            ->setParameter('n2b', 'Niveau 2b')
            ->setParameter('n2c', 'Niveau 2c');
        }
        

        if ($search->getCasTraite()) {

            $casTraite = $search->getCasTraite();

            if ($casTraite === 'oui') {
                $query = $query
                    ->andWhere('s.dateEvaluation IS NOT NULL');
            } elseif ($casTraite === 'non') {
                $query = $query
                    ->andWhere('s.dateEvaluation IS NULL');
            }
        }

        if ($search->getCasArchive()) {

            $casArchive = $search->getCasArchive();

            if ($casArchive === 'archive') {
                $query = $query
                    ->andWhere('s.casSusarEuV1 = true');
            } elseif ($casArchive === 'non_archive') {
                $query = $query
                    ->andWhere('s.casSusarEuV1 IS NULL');
            } elseif ($casArchive === 'tous') {
                // $query = $query
                //     ->andWhere('s.dateEvaluation IS NULL');
            }
        }

        if ($search->getAssessmentOutcome()) {
            $query = $query
                ->andWhere('spe.AssessmentOutcome = :aso')
                ->setParameter('aso', $search->getAssessmentOutcome());
        }

        if ($search->getWorldWideId()) {
            $query = $query
                ->andWhere($query->expr()->like('s.worldWide_id', ':wwi'))
                ->setParameter('wwi', '%' . $search->getWorldWideId() . '%');
        }

        if ($search->getNumEudract()) {
            $query = $query
                ->andWhere($query->expr()->like('s.num_eudract', ':nct'))
                ->setParameter('nct', '%' . $search->getNumEudract() . '%');
        }

        if ($search->getSponsorstudynumb()) {
            $query = $query
                ->andWhere($query->expr()->like('s.sponsorstudynumb', ':ssn'))
                ->setParameter('ssn', '%' . $search->getSponsorstudynumb() . '%');
        }

        if ($search->getCaseVersion()) {

            $caseVersion = $search->getCaseVersion();

            if ($caseVersion === 'cas_initial') {
                $query = $query
                    ->andWhere('s.DLPVersion = 0');
            } elseif ($caseVersion === 'follow_up') {
                $query = $query
                    ->andWhere('s.DLPVersion != 0');
            }
        }

        if ($search->getCasIME()) {

            $casIME = $search->getCasIME();

            if ($casIME === 'oui') {
                $query = $query
                    ->andWhere('s.CasIME = TRUE');
            } elseif ($casIME === 'non') {
                $query = $query
                    ->andWhere('s.CasIME = FALSE');
            }
        }

        if ($search->getCasDME()) {

            $casDME = $search->getCasDME();

            if ($casDME === 'oui') {
                $query = $query
                ->andWhere('s.CasDME = TRUE');
            } elseif ($casDME === 'non') {
                $query = $query
                ->andWhere('s.CasDME = FALSE');
            }
        }

        if ($search->getCasEurope()) {

            $casEurope = $search->getCasEurope();

            if ($casEurope === 'oui') {
                $query = $query
                ->andWhere('s.CasEurope = TRUE');
            } elseif ($casEurope === 'non') {
                $query = $query
                ->andWhere('s.CasEurope = FALSE');
            }
        }

        // // Ajout du tri par statusdate
        // $query->orderBy('s.statusdate', 'ASC');

        foreach ($orderCriteria as $criteria) {
            $field = $criteria['field'];
            $direction = $criteria['direction'] ?? 'ASC'; // Default to 'ASC' if not specified
            $query->addOrderBy('s.' . $field, $direction);
        }

        // $query->orderBy('s.statusdate', 'DESC');
        // Ajoutez la pagination
        // $query->setFirstResult(($page - 1) * $nbResuPage)
        //     ->setMaxResults($nbResuPage);        
        // dump($query->getQuery()->getSQL());

        return $query
            ->getQuery()
            ->getResult();
    }

    /**
     * Undocumented function
     *
     * @param array $orderCriteria
     * @return array
     */
//     public function findAllOrder(array $orderCriteria = [['field' => 'statusdate', 'direction' => 'ASC']],
//     int $page = 1,
//     int $nbResuPage = 10
// ): array
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
        $qb = $this->createQueryBuilder('se')
            ->select('COUNT(spe.id)')
            ->join('se.substancePts', 'sp')
            ->join('sp.substancePtEvals', 'spe')
            ->where('se.id = :susarEuId')
            ->andWhere('sp.active_substance_high_level = :substance')
            ->andWhere('sp.reactionmeddrapt = :pt')
            ->setParameter('susarEuId', $susarEuId)
            ->setParameter('substance', $substance)
            ->setParameter('pt', $pt);

        $count = $qb->getQuery()->getSingleScalarResult();

        return $count > 0;
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
