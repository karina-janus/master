<?php

/**
 * Task service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class TaskService.
 */
class TaskService implements TaskServiceInterface
{
    private TaskRepository $taskRepository;
    private PaginatorInterface $paginator;

    /**
     * TaskService constructor.
     *
     * @param TaskRepository     $repository The task repository
     * @param PaginatorInterface $paginator  The paginator
     */
    public function __construct(TaskRepository $repository, PaginatorInterface $paginator)
    {
        $this->taskRepository = $repository;
        $this->paginator = $paginator;
    }

    /**
     * Get a paginated list of tasks.
     *
     * @param int $page The page number
     *
     * @return PaginationInterface The paginated list of tasks
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->taskRepository->queryAll(),
            $page,
            TaskRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Get a paginated list of tasks by category.
     *
     * @param int      $page     The page number
     * @param Category $category The category entity
     *
     * @return PaginationInterface The paginated list of tasks
     */
    public function getPaginatedListByCategory(int $page, Category $category): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->taskRepository->queryByCategory($category),
            $page,
            UserRepository::PAGINATOR_ITEMS_PER_PAGE,
            [PaginatorInterface::SORT_FIELD_PARAMETER_NAME => 'task']
        );
    }

    /**
     * Save a task entity.
     *
     * @param Task $task The task entity
     */
    public function save(Task $task): void
    {
        if (!$this->taskRepository->findBy(['id' => $task->getId()])) {
            $task->setCreatedAt(new DateTimeImmutable());
        }
        $task->setUpdatedAt(new DateTimeImmutable());
        $this->taskRepository->save($task, true);
    }

    /**
     * Delete a task entity.
     *
     * @param Task $task The task entity
     */
    public function delete(Task $task): void
    {
        $this->taskRepository->remove($task, true);
    }
}
