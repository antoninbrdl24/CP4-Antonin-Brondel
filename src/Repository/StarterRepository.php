<?php

namespace App\Repository;

use App\Entity\Starter;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Starter>
 *
 * @method Starter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Starter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Starter[]    findAll()
 * @method Starter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StarterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Starter::class);
    }

    public function queryFindAllStarter(): Query
    {
        return $this->createQueryBuilder(alias:'s')->orderBy('s.id', 'ASC')->getQuery();
    }

    public function findLikeName(string $search): Query
    {
        $query = $this->createQueryBuilder('s')
            ->andWhere('s.name LIKE :search')
            ->setParameter('search', '%' . $search . '%');
        return $query->getQuery();
    }
//    /**
//     * @return Starter[] Returns an array of Starter objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Starter
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
