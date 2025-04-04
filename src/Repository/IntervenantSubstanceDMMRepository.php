<?php

namespace App\Repository;

use App\Entity\IntervenantSubstanceDMM;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IntervenantSubstanceDMM>
 *
 * @method IntervenantSubstanceDMM|null find($id, $lockMode = null, $lockVersion = null)
 * @method IntervenantSubstanceDMM|null findOneBy(array $criteria, array $orderBy = null)
 * @method IntervenantSubstanceDMM[]    findAll()
 * @method IntervenantSubstanceDMM[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntervenantSubstanceDMMRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IntervenantSubstanceDMM::class);
    }

    /**
     * @return IntervenantSubstanceDMM[] Returns an array of IntervenantSubstanceDMM objects
     */
    public function findAllSortHL_SA(): array
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.active_substance_high_level', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return IntervenantSubstanceDMM[] Returns an array of IntervenantSubstanceDMM objects
     */
    public function findHL_SA_Rgp(): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.inactif = :val')
            ->setParameter('val', false)
            ->orderBy('i.active_substance_high_level', 'ASC')
            ->addGroupBy('i.active_substance_high_level')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * permet de retourner toutes les lignes de la table "intervenant_substance_dmm" qui ont pour high level substance name la valeur passée en paramètre
     * Nottamment utilisée au moment de l'import du fichier activ substance grouping, pour rattacher les lignes de "intervenant_substance_dmm"
     *
     * @param string $HL_SA : high level substance name
     * @return IntervenantSubstanceDMM[] : retourne un array d'objet IntervenantSubstanceDMM
     */
    public function findByHL_SA(string $HL_SA): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.active_substance_high_level = :val')
            ->setParameter('val', $HL_SA)
            ->andWhere('i.inactif = :val_2')
            ->setParameter('val_2', false)
            ->orderBy('i.id', 'ASC')
            // ->addGroupBy('i.active_substance_high_level')
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * permet de retourner toutes les lignes de la table "intervenant_substance_dmm" qui ont pour high level substance name la valeur passée en paramètre
     * Nottamment utilisée au moment de l'import du fichier activ substance grouping, pour rattacher les lignes de "intervenant_substance_dmm"
     *
     * @param string $HL_SA : high level substance name
     * @return IntervenantSubstanceDMM[] : retourne un array d'objet IntervenantSubstanceDMM
     */
    public function findByInHL_SA(string $HL_SA): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.active_substance_high_level LIKE :val')
            ->setParameter('val', '%' . $HL_SA . '%') // Ajout des % autour de la valeur
            ->andWhere('i.inactif = :val_2')
            ->setParameter('val_2', false)
            ->orderBy('i.id', 'ASC')
            // ->addGroupBy('i.active_substance_high_level')
            ->getQuery()
            ->getResult()
        ;
    }


    /**
     * permet de retourner toutes les lignes de la table "intervenant_substance_dmm" qui ont pour high level substance name la valeur passée en paramètre
     * Nottamment utilisée au moment de l'import du fichier activ substance grouping, pour rattacher les lignes de "intervenant_substance_dmm"
     * @param string $HL_SA
     * @return array
     */
    public function findContainingHL_SA(string $HL_SA): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('LOWER(:val) LIKE LOWER(CONCAT(\'%\', i.active_substance_high_level, \'%\'))')
            ->setParameter('val', $HL_SA) // La valeur passée en paramètre
            ->andWhere('i.inactif = :val_2')
            ->setParameter('val_2', false)
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
    /**
     * Permet de savoir si la substance qui est crée ou modifiée n'existe pas déjà (même substance avec un id différent)
     *
     * @param string $HL_SA : nom de la substance
     * @param integer $idIntSub : id de la substance en cours de modif (ou -1 si c'est une création)
     * @return boolean : 1 si la substance existe déjà, 0 sinon
     */
    public function isSubstanceExistActiv(string $HL_SA, int $idIntSub = -1): bool
    {
        $qb = $this->createQueryBuilder('i')
            ->andWhere('i.active_substance_high_level = :val')
            ->setParameter('val', $HL_SA)
            ->andWhere('i.inactif = false');
        if ($idIntSub !== -1) {
            $qb->andWhere('i.id != :val2')
                ->setParameter('val2', $idIntSub);
        }
        $results = $qb->getQuery()->getResult();

        return !empty($results);
    }

    /**
     * permet de savoir combien on a d'intervenant substance
     *
     * @param boolean $inactif  : est-ce qu'on compte les inactifs ou pas
     * @return integer  : retourne le nombre d'intervenantSubstance
     */
    public function nbIntSub(bool $inactif = false): int
    {
        return $this->createQueryBuilder('i')
            ->select('count(i.id)')
            ->where('i.inactif = :val')
            ->setParameter('val', $inactif)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * permet de retourner toutes les lignes de la table "intervenant_substance_dmm" qui ont pour high level substance name la valeur passée en paramètre
     * Nottamment utilisée au moment de l'import du fichier activ substance grouping, pour rattacher les lignes de "intervenant_substance_dmm"
     * dans le cadre de la reprise des données
     *
     * @param string $HL_SA : high level substance name
     * @return IntervenantSubstanceDMM[] : retourne un array d'objet IntervenantSubstanceDMM
     */
    public function findByHL_SA_avec_inactifs(string $HL_SA): array
    {
        // dump($HL_SA);
        $result = $this->createQueryBuilder('i')
            ->andWhere('i.active_substance_high_level = :val')
            ->setParameter('val', $HL_SA)
            ->andWhere('i.inactif = :val_2')
            ->setParameter('val_2', false)
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult();

        if (empty($result)) {
            // dump('vide');
            $result = $this->createQueryBuilder('i')
                ->andWhere('i.active_substance_high_level = :val')
                ->setParameter('val', $HL_SA)
                ->orderBy('i.id', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getResult();

            // if (empty($result)) {
            //     // dump('vide');
            // } else {
            //     // dump($result);
            // }


        } else {
            // dump($result);
        }
        return $result;
    }

    /**
     * Undocumented function
     *
     * @param [type] $value
     * @return IntervenantSubstanceDMM|null
     */
    public function findIntSubById($value): ?IntervenantSubstanceDMM
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function findConcatenatedDmmAndPoleCourt(): array
    {
        $qb = $this->createQueryBuilder('i')
            ->select("CONCAT(i.DMM, '/', i.pole_court) AS concatenated")
            ->andWhere('i.inactif = :value')
            ->setParameter('value', false)
            ->groupBy('i.DMM, i.pole_court')
            ->orderBy('i.DMM, i.pole_court');

        $results = $qb->getQuery()->getResult();

        // Transform the results into a suitable format for ChoiceType
        $choices = [];
        foreach ($results as $result) {
            $choices[$result['concatenated']] = $result['concatenated'];
        }

        return $choices;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function findEvaluateur(): array
    {
        $qb = $this->createQueryBuilder('i')
            ->select("i.evaluateur")
            // ->andWhere('i.inactif = :value')
            // ->setParameter('value', false)
            ->groupBy('i.evaluateur')
            ->orderBy('i.evaluateur');

        $results = $qb->getQuery()->getResult();

        // Transform the results into a suitable format for ChoiceType
        $choices = [];
        foreach ($results as $result) {
            $choices[$result['evaluateur']] = $result['evaluateur'];
        }

        return $choices;
    }



    //    /**
    //     * @return IntervenantSubstanceDMM[] Returns an array of IntervenantSubstanceDMM objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?IntervenantSubstanceDMM
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
