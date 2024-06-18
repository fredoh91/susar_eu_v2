<?php

namespace App\Repository;

use App\Entity\DME;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DME>
 *
 * @method DME|null find($id, $lockMode = null, $lockVersion = null)
 * @method DME|null findOneBy(array $criteria, array $orderBy = null)
 * @method DME[]    findAll()
 * @method DME[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DMERepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DME::class);
    }

//    /**
//     * @return DME[] Returns an array of DME objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DME
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
