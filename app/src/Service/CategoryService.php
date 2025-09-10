<?php

/**
 * Category Service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;

/**
 * @class CategoryService
 */
class CategoryService implements CategoryServiceInterface
{
    /**
     * Constructor.
     *
     * @param CategoryRepository $categoryRepository Category Repository
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

    /**
     * Save entity.
     *
     * @param Category $category Category Entity
     */
    public function save(Category $category): void
    {
        $this->categoryRepository->save($category);
    }
}
