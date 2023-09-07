<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface UserServiceInterface
{
    public function getPaginatedList(int $page): PaginationInterface;

    public function save (User $user) : void;

    public function delete (User $user) : void;
}