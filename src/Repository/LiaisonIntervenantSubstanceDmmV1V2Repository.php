<?php

namespace App\Repository;

use App\Entity\LiaisonIntervenantSubstanceDmmV1V2;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LiaisonIntervenantSubstanceDmmV1V2>
 */
class LiaisonIntervenantSubstanceDmmV1V2Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LiaisonIntervenantSubstanceDmmV1V2::class);
    }

//    /**
//     * @return LiaisonIntervenantSubstanceDmmV1V2[] Returns an array of LiaisonIntervenantSubstanceDmmV1V2 objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LiaisonIntervenantSubstanceDmmV1V2
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
