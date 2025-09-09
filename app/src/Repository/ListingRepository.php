<?php

/**
 * Listing Repository.
 */

namespace App\Repository;

use App\Entity\Listing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @class Task Repository
 *
 * @extends ServiceEntityRepository<Listing>
 */
class ListingRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Listing::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->createQueryBuilder('listing')
        ->select('listing', 'category')
        ->leftJoin('listing.category', 'category');
    }

    /**
     * Query all records by given category Id.
     *
     * @param int $categoryId Category Id
     *
     * @return QueryBuilder Query builder
     */
    public function queryAllByCategory(int $categoryId): QueryBuilder
    {
        return $this->createQueryBuilder('listing')
            ->andwhere('listing.category = :category')
            ->setParameter('category', $categoryId);
    }

    /**
     * Query record by given Id.
     *
     * @param int $id Listing Id
     *
     * @return Listing|null Listing Entity
     */
    public function queryById(int $id): ?Listing
    {
        return $this->createQueryBuilder('listing')
            ->andwhere('listing.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getoneOrNullResult();
    }

    /**
     * Save entity.
     *
     * @param Listing $listing Listing Entity
     */
    public function save(Listing $listing): void
    {
        $this->getEntityManager()->persist($listing);
        $this->getEntityManager()->flush();
    }

    /**
     * Delete entity.
     *
     * @param Listing $listing Listing Entity
     */
    public function delete(Listing $listing): void
    {
        $this->getEntityManager()->remove($listing);
        $this->getEntityManager()->flush();
    }

    //    /**
    //     * @return Listing[] Returns an array of Listing objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Listing
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
