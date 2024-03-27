<?php

namespace App\Repository;

use App\Entity\Indications;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Indications>
 *
 * @method Indications|null find($id, $lockMode = null, $lockVersion = null)
 * @method Indications|null findOneBy(array $criteria, array $orderBy = null)
 * @method Indications[]    findAll()
 * @method Indications[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndicationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Indications::class);
    }

    //    /**
    //     * @return Indications[] Returns an array of Indications objects
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

    //    public function findOneBySomeField($value): ?Indications
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
