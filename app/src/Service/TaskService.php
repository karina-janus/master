<?php

namespace App\Service;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class TaskService implements TaskServiceInterface
{
    private TaskRepository $taskRepository;
    private PaginatorInterface $paginator;

    /**
     * @param TaskRepository $repository
     */
    public function __construct(
        TaskRepository $repository,
        PaginatorInterface $paginator
    )
    {
        $this->taskRepository = $repository;
        $this->paginator = $paginator;
    }

    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->taskRepository->queryAll(),
            $page,
            TaskRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function save(Task $task): void
    {
        $this->taskRepository->save($task, true);
    }

    public function delete(Task $task): void
    {
        $this->taskRepository->remove($task, true);
    }
}