<?php

namespace App\Repository;

use App\Entity\MeddraMdHierarchy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MeddraMdHierarchy>
 */
class MeddraMdHierarchyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeddraMdHierarchy::class);
    }
    /**
     * @return MeddraMdHierarchy[] Returns an array of MeddraMdHierarchy objects
     */
    public function findCodePtByPtName($value): array
    {
        return $this->createQueryBuilder('m')
            ->select('m.PtCode, m.PtNameEn')
            ->andWhere('m.PtNameEn = :val')
            ->setParameter('val', $value)
            ->groupBy('m.PtCode, m.PtNameEn')
            ->orderBy('m.PtCode', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }


    //    /**
    //     * @return MeddraMdHierarchy[] Returns an array of MeddraMdHierarchy objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?MeddraMdHierarchy
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
