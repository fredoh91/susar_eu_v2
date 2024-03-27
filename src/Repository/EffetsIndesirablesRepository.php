<?php

namespace App\Repository;

use App\Entity\EffetsIndesirables;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EffetsIndesirables>
 *
 * @method EffetsIndesirables|null find($id, $lockMode = null, $lockVersion = null)
 * @method EffetsIndesirables|null findOneBy(array $criteria, array $orderBy = null)
 * @method EffetsIndesirables[]    findAll()
 * @method EffetsIndesirables[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EffetsIndesirablesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EffetsIndesirables::class);
    }

    //    /**
    //     * @return EffetsIndesirables[] Returns an array of EffetsIndesirables objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?EffetsIndesirables
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
