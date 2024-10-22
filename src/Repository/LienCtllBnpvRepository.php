<?php

namespace App\Repository;

use App\Entity\LienCtllBnpv;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LienCtllBnpv>
 *
 * @method LienCtllBnpv|null find($id, $lockMode = null, $lockVersion = null)
 * @method LienCtllBnpv|null findOneBy(array $criteria, array $orderBy = null)
 * @method LienCtllBnpv[]    findAll()
 * @method LienCtllBnpv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LienCtllBnpvRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LienCtllBnpv::class);
    }

    //    /**
    //     * @return LienCtllBnpv[] Returns an array of LienCtllBnpv objects
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

    //    public function findOneBySomeField($value): ?LienCtllBnpv
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
