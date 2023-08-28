<?php

namespace App\Service;

use Knp\Component\Pager\Pagination\PaginationInterface;

interface TaskServiceInterface
{
    public function getPaginatedList(int $page): PaginationInterface;
}