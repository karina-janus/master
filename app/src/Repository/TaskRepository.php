<?php

/*
 * Task repository.
 */

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class TaskRepository.
 *
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    /**
     * Number of items per page for pagination.
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * TaskRepository constructor.
     *
     * @param ManagerRegistry $registry The registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * Create a query builder to retrieve tasks by category.
     *
     * @param Category $category The category entity
     *
     * @return QueryBuilder The query builder
     */
    public function queryByCategory(Category $category): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select('task')
            ->where('category.id = :categoryId')
            ->join('task.category', 'category')
            ->setParameter('categoryId', $category->getId());
    }

    /**
     * Save a Task entity.
     *
     * @param Task $entity The Task entity
     * @param bool $flush  Whether to flush the changes immediately
     */
    public function save(Task $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Remove a Task entity.
     *
     * @param Task $entity The Task entity
     * @param bool $flush  Whether to flush the changes immediately
     */
    public function remove(Task $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Create a query builder for all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select(
                'partial task.{id, createdAt, updatedAt, title}',
                'partial category.{id, title}'
            )
            ->join('task.category', 'category')
            ->orderBy('task.updatedAt', 'DESC');
    }

    /**
     * Get or create a new QueryBuilder instance.
     *
     * @param QueryBuilder|null $queryBuilder The QueryBuilder instance
     *
     * @return QueryBuilder The QueryBuilder instance
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('task');
    }
}
