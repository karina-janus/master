<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Task;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface TaskServiceInterface
{
    public function getPaginatedList(int $page): PaginationInterface;

    public function getPaginatedListByCategory(int $page, Category $category): PaginationInterface;

    public function save (Task $task) : void;

    public function delete (Task $task) : void;
}