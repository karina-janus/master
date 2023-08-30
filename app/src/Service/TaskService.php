<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Task;
use App\Repository\TaskRepository;
use DateTimeImmutable;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class TaskService implements TaskServiceInterface
{
    private TaskRepository $taskRepository;
    private PaginatorInterface $paginator;

    public function __construct(
        TaskRepository        $repository,
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

    public function getPaginatedListByCategory(int $page, Category $category): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->taskRepository->queryByCategory($category),
            $page,
            TaskRepository::PAGINATOR_ITEMS_PER_PAGE,
            [PaginatorInterface::SORT_FIELD_PARAMETER_NAME => 'task']
        );
    }

    public function save(Task $task): void
    {
        if (!$this->taskRepository->findBy(['id' => $task->getId()])) {
            $task->setCreatedAt(new DateTimeImmutable());
        }
        $task->setUpdatedAt(new DateTimeImmutable());
        $this->taskRepository->save($task, true);
    }


    public function delete(Task $task): void
    {
        $this->taskRepository->remove($task, true);
    }
}