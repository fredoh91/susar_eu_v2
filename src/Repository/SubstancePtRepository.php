<?php

namespace App\Repository;

use App\Entity\SusarEU;
use App\Entity\SubstancePt;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<SubstancePt>
 *
 * @method SubstancePt|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubstancePt|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubstancePt[]    findAll()
 * @method SubstancePt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubstancePtRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubstancePt::class);
    }
    /**
     * Cette méthode permet de retourner une entité SubstancePt selon la substance et le libelle PT passés en paramètres
     *
     * @param [type] $activeSubstance
     * @param [type] $reactionMeddraPt
     * @return void
     */
    public function findByActiveSubstanceAndReactionMeddraPt($activeSubstance, $reactionMeddraPt)
    {
        return $this->createQueryBuilder('s')
            ->where('s.active_substance_high_level = :active_substance')
            ->andWhere('s.reactionmeddrapt = :reaction_meddra_pt')
            ->setParameter('active_substance', $activeSubstance)
            ->setParameter('reaction_meddra_pt', $reactionMeddraPt)
            ->getQuery()
            ->getOneOrNullResult();
    }
    /**
     * Cette méthode permet de savoir si une entité SubstancePt et une entité SusarEU sont liées
     *
     * @param SubstancePt $substancePt
     * @param SusarEU $susarEU
     * @return boolean
     */
    public function isLinkedToSusarEU(SubstancePt $substancePt, SusarEU $susarEU)
    {
        return $this->createQueryBuilder('s')
            ->where('s = :substance_pt')
            ->andWhere(':susar_eu MEMBER OF s.susarEUs')
            ->setParameter('substance_pt', $substancePt)
            ->setParameter('susar_eu', $susarEU)
            ->getQuery()
            ->getOneOrNullResult() !== null;
    }
    
    //    /**
    //     * @return SubstancePt[] Returns an array of SubstancePt objects
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

    //    public function findOneBySomeField($value): ?SubstancePt
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
