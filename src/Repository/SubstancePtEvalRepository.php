<?php

namespace App\Repository;

use App\Entity\SubstancePtEval;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SubstancePtEval>
 *
 * @method SubstancePtEval|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubstancePtEval|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubstancePtEval[]    findAll()
 * @method SubstancePtEval[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubstancePtEvalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubstancePtEval::class);
    }
    public function findExistingEval($susar, $substancePt)
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('e.susarEUs', 's')
            ->innerJoin('e.substancePts', 'sp')
            ->where('s.id = :susarId')
            ->andWhere('sp.id = :substancePtId')
            ->setParameter('susarId', $susar->getId())
            ->setParameter('substancePtId', $substancePt->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }
    //    /**
    //     * @return SubstancePtEval[] Returns an array of SubstancePtEval objects
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

    //    public function findOneBySomeField($value): ?SubstancePtEval
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
