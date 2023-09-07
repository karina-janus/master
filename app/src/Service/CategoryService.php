<?php

/**
 * Category service interface.
 */

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\NoteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CategoryService.
 */
class CategoryService implements CategoryServiceInterface
{
    /** @var CategoryRepository The category repository */
    private CategoryRepository $categoryRepository;

    /** @var NoteRepository The note repository */
    private NoteRepository $noteRepository;

    /** @var UserRepository The user repository */
    private UserRepository $taskRepository;

    /** @var PaginatorInterface The paginator */
    private PaginatorInterface $paginator;

    /**
     * CategoryService constructor.
     *
     * @param CategoryRepository $repository     The category repository
     * @param NoteRepository     $noteRepository The note repository
     * @param UserRepository     $taskRepository The user repository
     * @param PaginatorInterface $paginator      The paginator
     */
    public function __construct(CategoryRepository $repository, NoteRepository $noteRepository, UserRepository $taskRepository, PaginatorInterface $paginator)
    {
        $this->categoryRepository = $repository;
        $this->noteRepository = $noteRepository;
        $this->taskRepository = $taskRepository;
        $this->paginator = $paginator;
    }

    /**
     * Get a paginated list of categories.
     *
     * @param int $page The page number
     *
     * @return PaginationInterface The paginated list of categories
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->categoryRepository->queryAll(),
            $page,
            CategoryRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Check if a category can be deleted.
     *
     * @param Category $category The category entity
     *
     * @return bool Whether the category can be deleted
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

    /**
     * Save a category entity.
     *
     * @param Category $category The category entity
     */
    public function save(Category $category): void
    {
        if (!$this->categoryRepository->findBy(['id' => $category->getId()])) {
            $category->setCreatedAt(new \DateTimeImmutable());
        }
        $category->setUpdatedAt(new \DateTimeImmutable());
        $this->categoryRepository->save($category, true);
    }

    /**
     * Delete a category if it can be deleted.
     *
     * @param Category $category The category entity
     *
     * @return bool Whether the category was deleted
     */
    public function delete(Category $category): bool
    {
        if ($this->canBeDeleted($category)) {
            $this->categoryRepository->remove($category, true);

            return true;
        }

        return false;
    }
}
