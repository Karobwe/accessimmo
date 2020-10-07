<?php

namespace App\Repository;

use App\Entity\Housing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Housing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Housing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Housing[]    findAll()
 * @method Housing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HousingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Housing::class);
    }

    /**
     * @return Housing[] Returns an array of Housing objects
     */
    public function findByTerm($value)
    {
        $qb =  $this->createQueryBuilder('h');
        $qb
            ->join('App\Entity\Type', 't', Join::WITH, 't = h.type')
            ->join('App\Entity\Status', 's', Join::WITH, 's = h.status')
            ->join('App\Entity\Address', 'a', Join::WITH, 'a = h.address')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->like('LOWER(h.shortDescription)', ':term'),
                    $qb->expr()->like('LOWER(h.description)', ':term'),
                    $qb->expr()->like('LOWER(t.name)', ':term'),
                    $qb->expr()->like('LOWER(s.name)', ':term'),
                    $qb->expr()->like('LOWER(a.city)', ':term')
                )
            )
            ->setParameter('term', '%' . strtolower($value) . '%')
            ->orderBy('h.updatedAt', 'ASC');

        return $qb
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Housing[] Returns an array of Housing objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Housing
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
