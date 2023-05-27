<?php

namespace App\Service;

use App\Repository\TaskRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class TaskService implements TaskServiceInterface
{
    private TaskRepository $repository;
    private PaginatorInterface $paginator;

    /**
     * @param TaskRepository $repository
     */
    public function __construct(
        TaskRepository $repository,
        PaginatorInterface $paginator
    )
    {
        $this->repository = $repository;
        $this->paginator = $paginator;
    }

    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->repository->queryAll(),
            $page,
            TaskRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }
}