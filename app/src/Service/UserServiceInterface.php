<?php

/**
 * User service interface.
 */

namespace App\Service;

use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface UserServiceInterface.
 */
interface UserServiceInterface
{
    /**
     * Get a paginated list of users.
     *
     * @param int $page The page number
     *
     * @return PaginationInterface The paginated list of users
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Save a user entity.
     *
     * @param User $user The user entity
     */
    public function save(User $user): void;

    /**
     * Delete a user entity.
     *
     * @param User $user The user entity
     */
    public function delete(User $user): void;
}
