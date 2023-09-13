<?php

/**
 * Note service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Entity\Note;
use App\Repository\NoteRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class NoteService.
 */
class NoteService implements NoteServiceInterface
{
    private NoteRepository $noteRepository;
    private PaginatorInterface $paginator;

    /**
     * NoteService constructor.
     *
     * @param NoteRepository     $repository The note repository
     * @param PaginatorInterface $paginator  The paginator
     */
    public function __construct(NoteRepository $repository, PaginatorInterface $paginator)
    {
        $this->noteRepository = $repository;
        $this->paginator = $paginator;
    }

    /**
     * Get a paginated list of notes.
     *
     * @param int $page The page number
     *
     * @return PaginationInterface The paginated list of notes
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->noteRepository->queryAll(),
            $page,
            NoteRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Get a paginated list of notes by category.
     *
     * @param int      $page     The page number
     * @param Category $category Category entity
     *
     * @return PaginationInterface The paginated list of notes
     */
    public function getPaginatedListByCategory(int $page, Category $category): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->noteRepository->queryByCategory($category),
            $page,
            NoteRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save a note entity.
     *
     * @param Note $note The note entity
     */
    public function save(Note $note): void
    {
        if (!$this->noteRepository->findBy(['id' => $note->getId()])) {
            $note->setCreatedAt(new \DateTimeImmutable());
        }
        $note->setUpdatedAt(new \DateTimeImmutable());
        $this->noteRepository->save($note, true);
    }

    /**
     * Delete a note entity.
     *
     * @param Note $note The note entity
     */
    public function delete(Note $note): void
    {
        $this->noteRepository->remove($note, true);
    }
}
