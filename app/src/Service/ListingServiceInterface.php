<?php

/**
 * Listing Service Interface.
 */

namespace App\Service;

use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface ListingServiceInterface.
 */
interface ListingServiceInterface
{
    /**
     * Get paginated listings.
     *
     * @param int      $page       Page number
     * @param int|null $categoryId Category Id
     *
     * @return PaginationInterface Pagination Interface
     */
    public function getPaginatedListings(int $page, ?int $categoryId): PaginationInterface;
}
