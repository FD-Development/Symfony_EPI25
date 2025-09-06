<?php

/**
 * Listing Service.
 */

namespace App\Service;

use App\Repository\ListingRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @class ListingService
 */
class ListingService
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
     * Get paginated listings.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface PaginationInterface
     */
    public function getPaginatedListings(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->listingRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => ['listing.title', 'listing.activatedAt'],
                'defaultSortFieldName' => 'listing.activatedAt',
                'defaultSortDirection' => 'desc',
            ]
        );
    }
}
