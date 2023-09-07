<?php

/**
 * Note repository.
 */

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Note;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class NoteRepository.
 *
 * @extends ServiceEntityRepository<Note>
 */
class NoteRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in configuration files.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int PAGINATOR_ITEMS_PER_PAGE Items per page.
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * NoteRepository constructor.
     *
     * @param ManagerRegistry $registry The registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Note::class);
    }

    /**
     * Query notes by category.
     *
     * @param Category $category The category entity
     *
     * @return QueryBuilder The query builder
     */
    public function queryByCategory(Category $category): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select('note')
            ->where('category.id = :categoryId')
            ->join('note.category', 'category')
            ->setParameter('categoryId', $category->getId());
    }

    /**
     * Save a note entity.
     *
     * @param Note $entity The note entity
     * @param bool $flush  Whether to flush the changes immediately
     */
    public function save(Note $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Remove a note entity.
     *
     * @param Note $entity The note entity
     * @param bool $flush  Whether to flush the changes immediately
     */
    public function remove(Note $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Create a query builder for all records.
     *
     * @return QueryBuilder The query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select(
                'partial note.{id, createdAt, updatedAt, title, content}',
                'partial category.{id, title}'
            )
            ->join('note.category', 'category')
            ->orderBy('note.updatedAt', 'DESC');
    }

    /**
     * Get or create a new query builder.
     *
     * @param QueryBuilder|null $queryBuilder The query builder
     *
     * @return QueryBuilder The query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('note');
    }
}
