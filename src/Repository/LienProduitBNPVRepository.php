<?php

namespace App\Repository;

use App\Entity\LienProduitBNPV;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LienProduitBNPV>
 *
 * @method LienProduitBNPV|null find($id, $lockMode = null, $lockVersion = null)
 * @method LienProduitBNPV|null findOneBy(array $criteria, array $orderBy = null)
 * @method LienProduitBNPV[]    findAll()
 * @method LienProduitBNPV[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LienProduitBNPVRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LienProduitBNPV::class);
    }

    //    /**
    //     * @return LienProduitBNPV[] Returns an array of LienProduitBNPV objects
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

    //    public function findOneBySomeField($value): ?LienProduitBNPV
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
