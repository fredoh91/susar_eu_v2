<?php

namespace App\Repository;

use App\Entity\ActiveSubstanceGroupingRepriseDonnees;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActiveSubstanceGroupingRepriseDonnees>
 *
 * @method ActiveSubstanceGrouping|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActiveSubstanceGrouping|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActiveSubstanceGrouping[]    findAll()
 * @method ActiveSubstanceGrouping[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActiveSubstanceGroupingRepriseDonneesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActiveSubstanceGroupingRepriseDonnees::class);
    }

    public function inactiveTout()
    {
        $query = $this->createQueryBuilder('c')
            ->update(ActiveSubstanceGroupingRepriseDonnees::class, 'a')
            ->set('a.inactif', ':nouveauStatut')
            ->setParameter('nouveauStatut', true)
            ->getQuery();

        $query->execute();
    }
    public function effaceTout()
    {
        $connection = $this->getEntityManager()->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=0;');
        $connection->executeStatement($platform->getTruncateTableSQL('active_substance_grouping_reprise_donnees', true));
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=1;');
    }
    /**
    * @return ActiveSubstanceGroupingRepriseDonnees[] Returns an array of ActiveSubstanceGroupingRepriseDonnees objects
    */
    public function findByActif(): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.inactif = :val')
            ->setParameter('val', false)
            ->orderBy('a.ActiveSubstanceHighLevel', 'ASC')
            ->addOrderBy('a.ActiveSubstanceLowLevel', 'ASC')
        //    ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    //    /**
    //     * @return ActiveSubstanceGrouping[] Returns an array of ActiveSubstanceGrouping objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ActiveSubstanceGrouping
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
