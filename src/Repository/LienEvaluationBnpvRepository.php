<?php

namespace App\Repository;

use App\Entity\LienEvaluationBnpv;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LienEvaluationBnpv>
 *
 * @method LienEvaluationBnpv|null find($id, $lockMode = null, $lockVersion = null)
 * @method LienEvaluationBnpv|null findOneBy(array $criteria, array $orderBy = null)
 * @method LienEvaluationBnpv[]    findAll()
 * @method LienEvaluationBnpv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LienEvaluationBnpvRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LienEvaluationBnpv::class);
    }

    //    /**
    //     * @return LienEvaluationBnpv[] Returns an array of LienEvaluationBnpv objects
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

    //    public function findOneBySomeField($value): ?LienEvaluationBnpv
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
