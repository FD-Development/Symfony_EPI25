<?php

/**
 * Category Service.
 */

namespace App\Service;

use App\Repository\CategoryRepository;

/**
 * @class CategoryService
 */
class CategoryService implements CategoryServiceInterface
{
    /**
     * Constructor.
     */
    public function __construct(private readonly CategoryRepository $categoryRepository)
    {
    }

    /**
     * Get all Categories.
     *
     * @return array Array
     */
    public function getAll(): array
    {
        return $this->categoryRepository->queryAll();
    }
}
