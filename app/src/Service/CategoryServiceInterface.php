<?php

/**
 * Category Service Interface.
 */

namespace App\Service;

use App\Entity\Category;

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

    /**
     * Save entity.
     *
     * @param Category $category Category Entity
     */
    public function save(Category $category): void;

    /**
     * Delete entity.
     *
     * @param Category $category Category Entity
     */
    public function delete(Category $category): void;
}
