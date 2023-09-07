<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\NoteRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class CategoryService implements CategoryServiceInterface
{
    private CategoryRepository $categoryRepository;

    private NoteRepository $noteRepository;

    private UserRepository $taskRepository;

    private PaginatorInterface $paginator;

    public function __construct(
        CategoryRepository $repository,
        NoteRepository     $noteRepository,
        UserRepository     $taskRepository,
        PaginatorInterface $paginator
    )
    {
        $this->categoryRepository = $repository;
        $this->noteRepository = $noteRepository;
        $this->taskRepository = $taskRepository;
        $this->paginator = $paginator;
    }

    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->categoryRepository->queryAll(),
            $page,
            CategoryRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }



    /**
     * Can Category be deleted?
     *
     * @param Category $category Category entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Category $category): bool
    {
        try {
            $result = $this->noteRepository->countByCategory($category);
            $result += $this->taskRepository->countByCategory($category);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException) {
            return false;
        }
    }

    public function save(Category $category): void
    {
        if (!$this->categoryRepository->findBy(['id' => $category->getId()])) {
            $category->setCreatedAt(new DateTimeImmutable());
        }
        $category->setUpdatedAt(new DateTimeImmutable());
        $this->categoryRepository->save($category, true);
    }

    public function delete(Category $category): bool
    {
        if ($this->canBeDeleted($category)) {
            $this->categoryRepository->remove($category, true);
            return true;
        }
        else return false;
    }
}