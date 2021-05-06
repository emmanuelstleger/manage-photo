<?php

namespace App\Repository;

use App\Entity\GaleriePhp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GaleriePhp|null find($id, $lockMode = null, $lockVersion = null)
 * @method GaleriePhp|null findOneBy(array $criteria, array $orderBy = null)
 * @method GaleriePhp[]    findAll()
 * @method GaleriePhp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GaleriePhpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GaleriePhp::class);
    }

    // /**
    //  * @return GaleriePhp[] Returns an array of GaleriePhp objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GaleriePhp
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
