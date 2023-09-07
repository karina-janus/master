<?php

/**
 * User service.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class UserService.
 */
class UserService implements UserServiceInterface
{
    private UserRepository $userRepository;
    private PaginatorInterface $paginator;

    /**
     * UserService constructor.
     *
     * @param UserRepository     $repository The user repository
     * @param PaginatorInterface $paginator  The paginator
     */
    public function __construct(UserRepository $repository, PaginatorInterface $paginator)
    {
        $this->userRepository = $repository;
        $this->paginator = $paginator;
    }

    /**
     * Get a paginated list of users.
     *
     * @param int $page The page number
     *
     * @return PaginationInterface The paginated list of users
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->userRepository->queryAll(),
            $page,
            UserRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save a user entity.
     *
     * @param User $user The user entity
     */
    public function save(User $user): void
    {
        $this->userRepository->save($user, true);
    }

    /**
     * Delete a user entity.
     *
     * @param User $user The user entity
     */
    public function delete(User $user): void
    {
        $this->userRepository->remove($user, true);
    }
}
