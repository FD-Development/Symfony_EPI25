<?php

/**
 * Category Service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;

/**
 * Class CategoryService.
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
     * Get Category by Id.
     *
     * @param int $id Category id
     *
     * @return Category|null Category Entity
     */
    public function getOne(int $id): ?Category
    {
        return $this->categoryRepository->queryById($id);
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

    /**
     * Delete entity.
     *
     * @param Category $category Category Entity
     */
    public function delete(Category $category): void
    {
        $this->categoryRepository->delete($category);
    }
}
