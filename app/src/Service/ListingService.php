<?php

/**
 * Listing Service.
 */

namespace App\Service;

use App\Repository\ListingRepository;

/**
 * @class ListingService
 */
class ListingService
{
    /**
     * Constructor.
     * @param ListingRepository $listingRepository
     */
    public function __construct(private readonly ListingRepository $listingRepository)
    {

    }

    /**
     * Get all listings.
     */
    public function getListings(): array
    {
        return $this->listingRepository->findAll();
    }
}
