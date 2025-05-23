<?php

namespace App\Service;

use App\Entity\SearchSusarEU;
use App\Repository\SusarEURepository;
use Doctrine\ORM\EntityManagerInterface;

class SusarEUQueryService
{
    private SusarEURepository $susarEURepository;

    public function __construct(SusarEURepository $susarEURepository)
    {
        $this->susarEURepository = $susarEURepository;
    }


    /**
     * retourne la liste des SUSARs recherchés par un formulaire du type SearchListeEvalSusarType
     *
     * @param SearchListeEvalSusar $search
     * @return Susar|null
     */

    public function findBySearchSusarEuListe(
        SearchSusarEU $search,
        array $orderCriteria = [['field' => 'statusdate', 'direction' => 'ASC']]
    ): ?array {


        $query = $this->susarEURepository
            ->createQueryBuilder('s')
            ->distinct();
        $query->leftJoin('s.intervenantSubstanceDMMs', 'isd');        // Je mets un left join pour les cas où il n'y a pas d'intervenantSubstanceDMMs et ainsi pouvoir retrouver les susars de ce type
        $query->join('s.Medicament', 'med');
        $query->join('s.EffetsIndesirables', 'ei');
        // $query->leftJoin('s.Medicament', 'med');
        // $query->leftJoin('s.EffetsIndesirables', 'ei');
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

            // if ($Eval === "_non attribué_") {
            //     $query = $query
            //         ->andWhere($query->expr()->isNull('isd.evaluateur'));
            // } else {
                $query = $query
                    ->andWhere($query->expr()->eq('isd.evaluateur', ':eval'))
                    ->setParameter('eval', $Eval);
            // }
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
                // $query = $query
                //     ->andWhere('s.casSusarEuV1 = true');
                $query = $query
                    ->andWhere('s.casSusarEuV1 = 1');
            } elseif ($casArchive === 'non_archive') {
                // $query = $query
                //     ->andWhere('s.casSusarEuV1 IS NULL');
                $query = $query
                    ->andWhere('s.casSusarEuV1 = 0');
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
}
