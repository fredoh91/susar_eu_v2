<?php

namespace App\Repository;

use App\Entity\ActiveSubstanceGrouping;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActiveSubstanceGrouping>
 *
 * @method ActiveSubstanceGrouping|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActiveSubstanceGrouping|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActiveSubstanceGrouping[]    findAll()
 * @method ActiveSubstanceGrouping[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActiveSubstanceGroupingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActiveSubstanceGrouping::class);
    }

    public function inactiveTout()
    {
        $query = $this->createQueryBuilder('c')
            ->update(ActiveSubstanceGrouping::class, 'a')
            ->set('a.inactif', ':nouveauStatut')
            ->setParameter('nouveauStatut', true)
            ->getQuery();

        $query->execute();
    }

       /**
        * @return ActiveSubstanceGrouping[] Returns an array of ActiveSubstanceGrouping objects
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
