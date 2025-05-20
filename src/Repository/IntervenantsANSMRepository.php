<?php

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;
use App\Entity\IntervenantsANSM;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<IntervenantsANSM>
 *
 * @method IntervenantsANSM|null find($id, $lockMode = null, $lockVersion = null)
 * @method IntervenantsANSM|null findOneBy(array $criteria, array $orderBy = null)
 * @method IntervenantsANSM[]    findAll()
 * @method IntervenantsANSM[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntervenantsANSMRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IntervenantsANSM::class);
    }

    public function getFormattedChoices(): array
    {
        $qb = $this->createQueryBuilder('i')
            ->select('i.id, i.evaluateur, i.DMM, i.pole_court, i.nom, i.prenom')
            ->where('i.inactif = 0')
            ->orderBy('i.OrdreTri', 'ASC')
            ->getQuery();
        // dump($qb->getSQL());
        $results = $qb->getArrayResult();

        $choices = [];
        foreach ($results as $result) {

            // dump($result['prenom']);

            $evaluateur = $result['evaluateur'];
            $dmm = $result['DMM'];
            $poleCourt = $result['pole_court'];
            $affichage = $result['prenom'] . ' ' . $result['nom'] . ' (' . $result['DMM'] . '/' . $result['pole_court'] . ')';
            $id = $result['id'];

            // $choices[$evaluateur] = "$evaluateur|$dmm|$poleCourt|$affichage";
            $choices[$id] = "$evaluateur|$dmm|$poleCourt|$affichage|$id";
        }
        // dump($choices);
        return $choices;
    }

    public function findFirstEvaluatorByName($userName)
    {
        return $this->createQueryBuilder('i')
            ->where('i.evaluateur = :userName')
            ->setParameter('userName', $userName)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    /**
     * Permet de ne retourner que les intervenants actifs, nottamment pour le choix d'un intervenant a affecter a un IntervenantSubstanceDMM
     *
     * @return QueryBuilder
     */
    public function findActifsQryBld(): QueryBuilder
    {
        return $this->createQueryBuilder('i')
            ->where('i.inactif = 0')
            ->orderBy('i.OrdreTri', 'ASC');
    }

    // /**
    //  * Undocumented function
    //  *
    //  * @return \Doctrine\ORM\QueryBuilder
    //  */
    // public function findAllGroupByIdentityQryBld(): QueryBuilder
    // {
    //     return $this->createQueryBuilder('i')
    //         ->select('i.prenom, i.nom, i.evaluateur')
    //         ->groupBy('i.prenom, i.nom, i.evaluateur')
    //         ->orderBy('i.nom', 'ASC')
    //         ->addOrderBy('i.prenom', 'ASC')
    //         ->addOrderBy('i.evaluateur', 'ASC');
    // }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getAllUserChoices(): array
    {
        $qb = $this->createQueryBuilder('i')
            ->select('i.prenom, i.nom, i.evaluateur')
            ->groupBy('i.prenom, i.nom, i.evaluateur')
            // ->orderBy('i.nom', 'ASC')
            // ->addOrderBy('i.prenom', 'ASC')
            ->addOrderBy('i.evaluateur', 'ASC');
        $results = $qb->getQuery()->getArrayResult();

        $choices = [];
        foreach ($results as $row) {
            // $label = $row['prenom'] . ' ' . $row['nom'] . ' / ' . $row['evaluateur'];
            // $choices[$label] = $row['evaluateur']; // username
            $choices[$row['evaluateur']] = $row['evaluateur'];
        }

        return $choices;
    }


    //    /**
    //     * @return IntervenantsANSM[] Returns an array of IntervenantsANSM objects
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

    //    public function findOneBySomeField($value): ?IntervenantsANSM
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
