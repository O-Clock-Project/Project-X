<?php

namespace App\Repository;

use App\Entity\WarningBookmark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WarningBookmark|null find($id, $lockMode = null, $lockVersion = null)
 * @method WarningBookmark|null findOneBy(array $criteria, array $orderBy = null)
 * @method WarningBookmark[]    findAll()
 * @method WarningBookmark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarningBookmarkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WarningBookmark::class);
    }

//    /**
//     * @return WarningBookmark[] Returns an array of WarningBookmark objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WarningBookmark
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
