<?php

namespace App\Repository;

use App\Entity\IntervenantSubstanceDMMSubstance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IntervenantSubstanceDMMSubstance>
 *
 * @method IntervenantSubstanceDMMSubstance|null find($id, $lockMode = null, $lockVersion = null)
 * @method IntervenantSubstanceDMMSubstance|null findOneBy(array $criteria, array $orderBy = null)
 * @method IntervenantSubstanceDMMSubstance[]    findAll()
 * @method IntervenantSubstanceDMMSubstance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntervenantSubstanceDMMSubstanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IntervenantSubstanceDMMSubstance::class);
    }

//    /**
//     * @return IntervenantSubstanceDMMSubstance[] Returns an array of IntervenantSubstanceDMMSubstance objects
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

//    public function findOneBySomeField($value): ?IntervenantSubstanceDMMSubstance
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
