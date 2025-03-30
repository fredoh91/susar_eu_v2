<?php

namespace App\Repository;

use App\Entity\ImportCtll;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImportCtll>
 */
class ImportCtllRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImportCtll::class);
    }


   /**
    * @return ImportCtll[] Returns an array of ImportCtll objects
    */
   public function findByIdImport(int $idImportCtllFicExcel): array
   {
       return $this->createQueryBuilder('i')
           ->andWhere('i.ImportCtllFicExcel = :val')
           ->setParameter('val', $idImportCtllFicExcel)
           ->orderBy('i.id', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

//    /**
//     * @return ImportCtll[] Returns an array of ImportCtll objects
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

//    public function findOneBySomeField($value): ?ImportCtll
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
