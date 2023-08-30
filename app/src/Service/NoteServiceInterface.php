<?php

namespace App\Service;

use App\Entity\Note;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface NoteServiceInterface
{
    public function getPaginatedList(int $page): PaginationInterface;

    public function save (Note $note) : void;

    public function delete (Note $note) : void;
}