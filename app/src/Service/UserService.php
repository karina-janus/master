<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use function Symfony\Component\Translation\t;

class UserService implements UserServiceInterface
{

    private UserRepository $userRepository;
    private PaginatorInterface $paginator;

    public function __construct(
        UserRepository     $repository,
        PaginatorInterface $paginator
    )
    {
        $this->userRepository = $repository;
        $this->paginator = $paginator;
    }
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->userRepository->queryAll(),
            $page,
            UserRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function save (User $user) : void
    {
        $this->userRepository->save($user, true);
    }

    public function delete (User $user) : void
    {
        $this->userRepository->remove($user, true);
    }
}