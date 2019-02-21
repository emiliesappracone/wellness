<?php

namespace App\Repository;

use App\Entity\Abuse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Abuse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Abuse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Abuse[]    findAll()
 * @method Abuse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbuseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Abuse::class);
    }

    // /**
    //  * @return Abuse[] Returns an array of Abuse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Abuse
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
