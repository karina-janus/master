<?php

namespace App\Repository;


/**
 * Class RecordRepository.
 */
interface RecordRepositoryInterface
{
    /**
     * Find all.
     *
     * @return array[] Result
     *
     * @psalm-return array<int, array<string, mixed>>
     */
    public function findAll(): array;

    /**
     * Find one by Id.
     *
     * @param int $id Id
     *
     * @return array<string, mixed>|null Result
     */
    public function findOneById(int $id): ?array;
}