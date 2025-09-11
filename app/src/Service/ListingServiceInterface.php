<?php

/**
 * Listing Service Interface.
 */

namespace App\Service;

use App\Entity\Listing;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface ListingServiceInterface.
 */
interface ListingServiceInterface
{
    /**
     * Get paginated listings that are active.
     *
     * @param int      $page       Page number
     * @param int|null $categoryId Category Id
     *
     * @return PaginationInterface Pagination Interface
     */
    public function getActivatedPaginatedListings(int $page, ?int $categoryId): PaginationInterface;

    /**
     * Get paginated listings that are not active.
     *
     * @param int      $page       Page number
     * @param int|null $categoryId Category Id
     *
     * @return PaginationInterface Pagination Interface
     */
    public function getNonActivatedPaginatedListings(int $page, ?int $categoryId): PaginationInterface;

    /**
     * Get Listing by Id.
     *
     * @param int $id Listing id
     *
     * @return Listing|null Listing Entity
     */
    public function getOne(int $id): ?Listing;

    /**
     * Save entity.
     *
     * @param Listing $listing Listing Entity
     */
    public function save(Listing $listing): void;

    /**
     * Activate listing.
     *
     * @param Listing $listing Listing Entity
     */
    public function activate(Listing $listing): void;

    /**
     * Delete entity.
     *
     * @param Listing $listing Listing Entity
     */
    public function delete(Listing $listing): void;
}
