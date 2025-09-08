<?php

/**
 * Category Service Interface.
 */

namespace App\Service;

/**
 * @class CategoryServiceInterface
 */
interface CategoryServiceInterface
{
    /**
     * Get all categories.
     *
     * @return array Array
     */
    public function getAll(): array;
}
