<?php

/**
 * Category service interface.
 */

namespace App\Service;

use App\Entity\Category;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface CategoryServiceInterface.
 */
interface CategoryServiceInterface
{
    /**
     * Get a paginated list of categories.
     *
     * @param int $page The page number
     *
     * @return PaginationInterface The paginated list of categories
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Save a category entity.
     *
     * @param Category $category The category entity
     */
    public function save(Category $category): void;

    /**
     * Delete a category if it can be deleted.
     *
     * @param Category $category The category entity
     *
     * @return bool Whether the category was deleted
     */
    public function delete(Category $category): bool;

    /**
     * Check if a category can be deleted.
     *
     * @param Category $category The category entity
     *
     * @return bool Whether the category can be deleted
     */
    public function canBeDeleted(Category $category): bool;
}
