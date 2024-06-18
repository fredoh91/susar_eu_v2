<?php

namespace App\Repository;

use App\Entity\IME;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IME>
 *
 * @method IME|null find($id, $lockMode = null, $lockVersion = null)
 * @method IME|null findOneBy(array $criteria, array $orderBy = null)
 * @method IME[]    findAll()
 * @method IME[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IMERepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IME::class);
    }

//    /**
//     * @return IME[] Returns an array of IME objects
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

//    public function findOneBySomeField($value): ?IME
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
