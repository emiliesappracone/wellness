<?php

namespace App\Repository;

use App\Entity\Provider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Provider|null find($id, $lockMode = null, $lockVersion = null)
 * @method Provider|null findOneBy(array $criteria, array $orderBy = null)
 * @method Provider[]    findAll()
 * @method Provider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProviderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Provider::class);
    }

    /**
     * @return provider[] Returns an array of Services objects
     */
    public function findAllProviders($first)
    {
        if ($first != 0) {
            $first = ($first - 1) * 6;
        }
        $qb = $this->createQueryBuilder('sp');
        $query = $qb->setMaxResults(6)
            ->setFirstResult($first)
            ->getQuery();
        return $query->getResult();
    }
    public function findByAll($whats, $first, $all)
    {
        if ($first != 0) {
            $first = ($first - 1) * 6;
        }
        $qb = $this->createQueryBuilder('sp');
        $locality = $whats['locality'];
        $service = $whats['services'];
        $provider = $whats['provider'];
        $arrayOfParameters = [];
        if ($locality) {
            $qb->addSelect('lc')
                ->join('sp.locality', 'lc')
                ->andWhere('lc.locality = :locality');
            $arrayOfParameters['locality'] = $locality;
        }
        if ($service) {
            $qb->addSelect('s')
                ->join('sp.services', 's')
                ->andWhere('s.id = :id');
            $arrayOfParameters['id'] = $service;
        }
        if ($provider) {
            $qb->andWhere('sp.name = :name');
            $arrayOfParameters['name'] = $provider;
        }
        if($all){
            $qb->setMaxResults(6)
                ->setFirstResult($first);
        }
        $query = $qb->setParameters($arrayOfParameters)
            ->getQuery();
        return $query->getResult();
    }
}
