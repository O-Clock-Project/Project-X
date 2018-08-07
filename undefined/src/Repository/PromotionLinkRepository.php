<?php

namespace App\Repository;

use App\Entity\PromotionLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PromotionLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method PromotionLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method PromotionLink[]    findAll()
 * @method PromotionLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionLinkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PromotionLink::class);
    }

//    /**
//     * @return PromotionLink[] Returns an array of PromotionLink objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PromotionLink
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
