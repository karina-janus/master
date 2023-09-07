<?php

/**
 * Task service interface.
 */

namespace App\Service;

use App\Entity\Category;
use App\Entity\Task;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface TaskServiceInterface.
 */
interface TaskServiceInterface
{
    /**
     * Get a paginated list of tasks.
     *
     * @param int $page The page number
     *
     * @return PaginationInterface The paginated list of tasks
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Get a paginated list of tasks by category.
     *
     * @param int      $page     The page number
     * @param Category $category The category entity
     *
     * @return PaginationInterface The paginated list of tasks
     */
    public function getPaginatedListByCategory(int $page, Category $category): PaginationInterface;

    /**
     * Save a task entity.
     *
     * @param Task $task The task entity
     */
    public function save(Task $task): void;

    /**
     * Delete a task entity.
     *
     * @param Task $task The task entity
     */
    public function delete(Task $task): void;
}
