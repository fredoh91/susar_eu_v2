<?php

namespace App\Repository;

use App\Entity\IntervenantsANSM;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IntervenantsANSM>
 *
 * @method IntervenantsANSM|null find($id, $lockMode = null, $lockVersion = null)
 * @method IntervenantsANSM|null findOneBy(array $criteria, array $orderBy = null)
 * @method IntervenantsANSM[]    findAll()
 * @method IntervenantsANSM[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntervenantsANSMRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IntervenantsANSM::class);
    }

    public function getFormattedChoices(): array
    {
        $qb = $this->createQueryBuilder('i')
            ->select('i.evaluateur, i.DMM, i.pole_court')
            ->orderBy('i.evaluateur', 'ASC')
            ->getQuery();

        $results = $qb->getResult();

        $choices = [];
        foreach ($results as $result) {
            $evaluateur = $result['evaluateur'];
            $dmm = $result['DMM'];
            $poleCourt = $result['pole_court'];

            $choices[$evaluateur] = "$evaluateur|$dmm|$poleCourt";
        }

        return $choices;
    }

    public function findFirstEvaluatorByName($userName)
    {
        return $this->createQueryBuilder('i')
            ->where('i.evaluateur = :userName')
            ->setParameter('userName', $userName)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return IntervenantsANSM[] Returns an array of IntervenantsANSM objects
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

    //    public function findOneBySomeField($value): ?IntervenantsANSM
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
