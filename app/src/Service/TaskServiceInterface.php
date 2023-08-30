<?php

namespace App\Service;

use App\Entity\Task;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface TaskServiceInterface
{
    public function getPaginatedList(int $page): PaginationInterface;

    public function save (Task $task) : void;
}