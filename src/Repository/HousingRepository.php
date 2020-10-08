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

    /**
     * @return Housing[] Returns an array of Housing objects
     */
    public function advancedSearch($queryData)
    {
        dump($queryData);
        $qb =  $this->createQueryBuilder('h');
        $queryParameters = [];

        $qb
            ->join('App\Entity\Type', 't', Join::WITH, 't = h.type')
            ->join('App\Entity\Status', 's', Join::WITH, 's = h.status')
            ->join('App\Entity\Address', 'a', Join::WITH, 'a = h.address');

        if(!empty($queryData['keywords']))
        {
            $keywords = $queryData['keywords'];
            for($i = 0; $i < count($keywords); $i++) {
                $paramValue = strtolower($keywords[$i]);
                $paramName = ":word" . $i;
                $qb->orWhere(
                    $qb->expr()->orX(
                        $qb->expr()->like('LOWER(h.shortDescription)', $paramName),
                        $qb->expr()->like('LOWER(h.description)', $paramName),
                        $qb->expr()->like('LOWER(a.city)', $paramName)
                    )
                );

                $queryParameters['word' . $i] = '%' . $paramValue . '%';
            }
        }

        if($queryData['typeId']) {
            $typeId = intval($queryData['typeId']);
            $qb->orWhere(
                $qb->where('h.type_id = :typeId')
            );
            $queryParameters['typeId'] = $typeId;
        }

        if($queryData['statusId']) {
            $statusId = intval($queryData['statusId']);
            $qb->orWhere(
                $qb->where('h.status = :statusId')
            );
            $queryParameters['statusId'] = $statusId;
        }

        $minPrice = $queryData['price']['min'];
        $maxPrice = $queryData['price']['max'];
        $minArea = $queryData['area']['min'];
        $maxArea = $queryData['area']['max'];
        $qb->orWhere(
            $qb->expr()->orX(
                $qb->expr()->between('h.price', $minPrice, $maxPrice),
                $qb->expr()->between('h.floorArea', $minArea, $maxArea)
            )
        );

        if($queryData['sizes']) {
            foreach ($queryData['sizes'] as $key => $value) {
                $qb->orWhere(
                    $qb->expr()->eq('h.bedroomCount', $value)
                );
            }
        }

        $qb->setParameters($queryParameters);
        $qb->orderBy('h.updatedAt', 'ASC');
        //dd($qb->getQuery());

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
