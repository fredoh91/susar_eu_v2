<?php

namespace App\Repository;

use App\Entity\PaysEurope;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PaysEurope>
 *
 * @method PaysEurope|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaysEurope|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaysEurope[]    findAll()
 * @method PaysEurope[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaysEuropeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaysEurope::class);
    }

    //    /**
    //     * @return PaysEurope[] Returns an array of PaysEurope objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PaysEurope
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
