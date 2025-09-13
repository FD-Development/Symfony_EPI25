<?php

/**
 * Listing Service.
 */

namespace App\Service;

use App\Entity\Listing;
use App\Repository\ListingRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ListingService.
 */
class ListingService implements ListingServiceInterface
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in configuration files.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param ListingRepository  $listingRepository Listing Repository
     * @param PaginatorInterface $paginator         Paginator
     */
    public function __construct(private readonly ListingRepository $listingRepository, private readonly PaginatorInterface $paginator)
    {
    }

    /**
     * Get paginated active listings able to be filtered with category.
     *
     * @param int      $page       Page number
     * @param int|null $categoryId Category Id
     *
     * @return PaginationInterface Pagination Interface
     */
    public function getActivatedPaginatedListings(int $page, ?int $categoryId): PaginationInterface
    {
        if (null !== $categoryId) {
            $query = $this->listingRepository->queryAllByCategory($categoryId, true);
        } else {
            $query = $this->listingRepository->queryAll(true);
        }

        return $this->paginator->paginate(
            $query,
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => ['listing.title', 'listing.activatedAt'],
                'defaultSortFieldName' => 'listing.activatedAt',
                'defaultSortDirection' => 'desc',
            ]
        );
    }

    /**
     * Get paginated nonactive listings able to be filtered with category.
     *
     * @param int      $page       Page number
     * @param int|null $categoryId Category Id
     *
     * @return PaginationInterface Pagination Interface
     */
    public function getNonActivatedPaginatedListings(int $page, ?int $categoryId): PaginationInterface
    {
        if (null !== $categoryId) {
            $query = $this->listingRepository->queryAllByCategory($categoryId, false);
        } else {
            $query = $this->listingRepository->queryAll(false);
        }

        return $this->paginator->paginate(
            $query,
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => ['listing.title', 'listing.createdAt'],
                'defaultSortFieldName' => 'listing.createdAt',
                'defaultSortDirection' => 'asc',
            ]
        );
    }

    /**
     * Get Listing by Id.
     *
     * @param int $id Listing id
     *
     * @return Listing|null Listing Entity
     */
    public function getOne(int $id): ?Listing
    {
        return $this->listingRepository->queryById($id);
    }

    /**
     * Save entity.
     *
     * @param Listing $listing Listing Entity
     */
    public function save(Listing $listing): void
    {

        if (null === $listing->getId()) {
            $listing->setCreatedAt(new \DateTimeImmutable());
            $listing->setActivatedAt(null);
        }
        $this->listingRepository->save($listing);
    }

    /**
     * Activate listing.
     *
     * @param Listing $listing Listing Entity
     */
    public function activate(Listing $listing): void
    {
        $listing->setActivatedAt(new \DateTimeImmutable());
    }

    /**
     * Delete entity.
     *
     * @param Listing $listing Listing Entity
     */
    public function delete(Listing $listing): void
    {
        $this->listingRepository->delete($listing);
    }
}
