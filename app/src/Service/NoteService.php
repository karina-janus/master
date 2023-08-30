<?php

namespace App\Service;

use App\Entity\Note;
use App\Repository\NoteRepository;
use DateTimeImmutable;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class NoteService implements NoteServiceInterface
{
    private NoteRepository $noteRepository;
    private PaginatorInterface $paginator;


    public function __construct(
        NoteRepository $repository,
        PaginatorInterface $paginator
    )
    {
        $this->noteRepository = $repository;
        $this->paginator = $paginator;
    }

    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->noteRepository->queryAll(),
            $page,
            NoteRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function save(Note $note): void
    {
        if (!$this->noteRepository->findBy(['id' => $note->getId()])) {
            $note->setCreatedAt(new DateTimeImmutable());
        }
        $note->setUpdatedAt(new DateTimeImmutable());
        $this->noteRepository->save($note, true);
    }

    public function delete(Note $note): void
    {
        $this->noteRepository->remove($note, true);
    }
}