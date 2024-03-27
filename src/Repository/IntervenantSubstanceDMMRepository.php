<?php

namespace App\Repository;

use App\Entity\IntervenantSubstanceDMM;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IntervenantSubstanceDMM>
 *
 * @method IntervenantSubstanceDMM|null find($id, $lockMode = null, $lockVersion = null)
 * @method IntervenantSubstanceDMM|null findOneBy(array $criteria, array $orderBy = null)
 * @method IntervenantSubstanceDMM[]    findAll()
 * @method IntervenantSubstanceDMM[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntervenantSubstanceDMMRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IntervenantSubstanceDMM::class);
    }

    //    /**
    //     * @return IntervenantSubstanceDMM[] Returns an array of IntervenantSubstanceDMM objects
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

    //    public function findOneBySomeField($value): ?IntervenantSubstanceDMM
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
