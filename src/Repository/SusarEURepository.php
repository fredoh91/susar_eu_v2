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
    public function findBySearchSusarEuListe(SearchSusarEU $search): ?array
    {

        $query = $this->createQueryBuilder('s');

        $query->join('s.intervenantSubstanceDMMs', 'isd');
        $query->join('s.Medicament', 'med');
        $query->join('s.EffetsIndesirables', 'ei');

        if ($search->getSpecificcaseid()) {
            $query = $query
                ->andWhere('s.specificcaseid = :sci')
                ->setParameter('sci', $search->getSpecificcaseid());
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
            $query = $query
                ->andWhere($query->expr()->eq('isd.evaluateur', ':eval'))
                ->setParameter('eval', $Eval);
            
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
        // if ($search->getMasterId()) {
        //     $query = $query
        //         ->andWhere('s.master_id = :mi')
        //         ->setParameter('mi', $search->getMasterId());
        // }

        // if ($search->getDLPVersion()) {
        //     $query = $query
        //         ->andWhere('s.DLPVersion = :dv')
        //         ->setParameter('dv', $search->getDLPVersion());
        // }

        // if ($search->getCaseid()) {
        //     $query = $query
        //         ->andWhere('s.caseid = :ci')
        //         ->setParameter('ci', $search->getCaseid());
        // }

        // if ($search->getNumEudract()) {
        //     $query = $query
        //         ->andWhere('s.num_eudract = :ne')
        //         ->setParameter('ne', $search->getNumEudract());
        // }

        // if ($search->getWorldWideId()) {
        //     $query = $query
        //         ->andWhere('s.worldWide_id LIKE :wwi')
        //         ->setParameter('wwi', '%' . $search->getWorldWideId() . '%');
        // }

        // if ($search->getSponsorstudynumb()) {
        //     $query = $query
        //         ->andWhere('s.sponsorstudynumb = :ssn')
        //         ->setParameter('ssn', $search->getSponsorstudynumb());
        // }

        // if ($search->getStudytitle()) {
        //     $query = $query
        //         ->andWhere('s.studytitle LIKE :st')
        //         ->setParameter('st', '%' . $search->getStudytitle() . '%');
        // }

        // if ($search->getProductName()) {
        //     $query = $query
        //         ->andWhere('s.productName LIKE :pn')
        //         ->setParameter('pn', '%' . $search->getProductName() . '%');
        // }

        // if ($search->getSubstanceName()) {
        //     $query = $query
        //         ->andWhere('s.substanceName LIKE :sn')
        //         ->setParameter('sn', '%' . $search->getSubstanceName() . '%');
        // }

        // if ($search->getIndication()) {
        //     $query = $query
        //         ->andWhere('s.indication LIKE :if')
        //         ->setParameter('if', '%' . $search->getIndication() . '%');
        // }

        // if ($search->getIndicationEng()) {
        //     $query = $query
        //         ->andWhere('s.indication_eng LIKE :ie')
        //         ->setParameter('ie', '%' . $search->getIndicationEng() . '%');
        // }

        // // dd($search->getIntervenantANSM()->getDMMPoleCourt());
        // if ($search->getIntervenantANSM()) {
        //     $query = $query
        //         ->leftJoin('s.intervenantANSM', 'iANSM')
        //         ->andWhere('iANSM.DMM_pole_court LIKE :ia')
        //         ->setParameter('ia', '%' . $search->getIntervenantANSM()->getDMMPoleCourt() . '%');
        // }

        // // dd($search->getMesureAction()->getLibelle());
        // if ($search->getMesureAction()) {
        //     $query = $query
        //         ->leftJoin('s.MesureAction', 'ma')
        //         ->andWhere('ma.Libelle LIKE :ia')
        //         ->setParameter('ia', '%' . $search->getMesureAction()->getLibelle() . '%');
        // }

        // // if ($search->getDebutCreationDate()) {
        // //     $query = $query
        // //         ->andWhere('s.creationdate >= :dcd')
        // //         ->setParameter('dcd', $search->getDebutCreationDate());
        // // }

        // // if ($search->getFinCreationDate()) {
        // //     $query = $query
        // //         ->andWhere('s.creationdate <= :fcd')
        // //         ->setParameter('fcd', $search->getFinCreationDate());
        // // }

        // if ($search->getDebutStatusDate()) {
        //     $query = $query
        //         ->andWhere('s.statusdate >= :dsd')
        //         ->setParameter('dsd', $search->getDebutStatusDate());
        // }

        // if ($search->getFinStatusDate()) {
        //     $query = $query
        //         ->andWhere('s.statusdate <= :fsd')
        //         ->setParameter('fsd', $search->getFinStatusDate());
        // }

        // if ($search->getDebutDateImport()) {
        //     $query = $query
        //         ->andWhere('s.dateImport >= :ddi')
        //         ->setParameter('ddi', $search->getDebutDateImport());
        // }

        // if ($search->getFinDateImport()) {
        //     $query = $query
        //         ->andWhere('s.dateImport <= :fdi')
        //         ->setParameter('fdi', $search->getFinDateImport()->modify('+1 day'));
        // }

        // if ($search->getDebutDateAiguillage()) {
        //     $query = $query
        //         ->andWhere('s.dateAiguillage >= :dda')
        //         ->setParameter('dda', $search->getDebutDateAiguillage());
        // }

        // if ($search->getFinDateAiguillage()) {
        //     $query = $query
        //         ->andWhere('s.dateAiguillage <= :fda')
        //         ->setParameter('fda', $search->getFinDateAiguillage()->modify('+1 day'));
        // }

        // if ($search->getDebutDateEvaluation()) {
        //     $query = $query
        //         ->andWhere('s.dateEvaluation >= :dde')
        //         ->setParameter('dde', $search->getDebutDateEvaluation());
        // }

        // if ($search->getFinDateEvaluation()) {
        //     $query = $query
        //         ->andWhere('s.dateEvaluation <= :fde')
        //         ->setParameter('fde', $search->getFinDateEvaluation()->modify('+1 day'));
        // }

        // if ($search->getEvalue()) {
        //     if ($search->getEvalue() === 'Non') {
        //         $query = $query
        //             ->andWhere('s.dateEvaluation IS NULL');
        //     } elseif ($search->getEvalue() === 'Oui') {
        //         $query = $query
        //             ->andWhere('s.dateEvaluation IS NOT NULL');
        //     } else {}
        // }

        // if ($search->getAiguille()) {
        //     if ($search->getAiguille() === 'Non') {
        //         $query = $query
        //             ->andWhere('s.intervenantANSM IS NULL');
        //     } elseif ($search->getAiguille() === 'Oui') {
        //         $query = $query
        //             ->andWhere('s.intervenantANSM IS NOT NULL');
        //     } else {}
        // }

        // dump($query->getQuery()->getSQL());

        return $query
            ->getQuery()
            ->getResult();
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
