<?php

namespace App\Repository;

use App\Entity\SubstancePt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
