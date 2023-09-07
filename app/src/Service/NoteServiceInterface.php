<?php

/**
 * Note service interface.
 */

namespace App\Service;

use App\Entity\Note;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface NoteServiceInterface.
 */
interface NoteServiceInterface
{
    /**
     * Get a paginated list of notes.
     *
     * @param int $page The page number
     *
     * @return PaginationInterface The paginated list of notes
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Get a paginated list of notes by category.
     *
     * @param int  $page The page number
     * @param Note $note note entity
     *
     * @return PaginationInterface The paginated list of notes
     */
    public function getPaginatedListByCategory(int $page, Note $note): PaginationInterface;

    /**
     * Save a note entity.
     *
     * @param Note $note The note entity
     */
    public function save(Note $note): void;

    /**
     * Delete a note entity.
     *
     * @param Note $note The note entity
     */
    public function delete(Note $note): void;
}
