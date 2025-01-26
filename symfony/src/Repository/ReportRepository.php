<?php

namespace App\Repository;

use App\Entity\Report;
use Doctrine\DBAL\Types\Types;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Report>
 */
class ReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Report::class);
    }

    /**
     * Find all reports with optional filtering, sorting, and pagination
     * 
     * @param array $filters Additional filter criteria
     * @param array $orderBy Sorting parameters
     * @param int|null $limit Maximum number of results
     * @param int|null $offset Pagination offset
     * @return Report[]
     */
    public function findAll(
        array $filters = [], 
        array $orderBy = ['room' => 'DESC', 'date_time' => 'DESC'], 
        ?int $limit = null, 
        ?int $offset = null
    ): array  {
        $qb = $this->createQueryBuilder('r');
        // Apply filters
        $this->applyFilters($qb, $filters);

        // Apply ordering
        $this->applyOrderBy($qb, $orderBy);

        // Apply limit and offset
        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }
        if ($offset !== null) {
            $qb->setFirstResult($offset);
        }
        return $qb->getQuery()->getResult();
    }

    /**
     * Apply filters to the query builder
     * 
     * @param QueryBuilder $qb
     * @param array $filters
     */
    private function applyFilters(QueryBuilder $qb, array $filters): void
    {
        foreach ($filters as $field => $value) {
            if ($field === 'date_from') {
                $qb->andWhere("r.date_time >= :$field")
                    ->setParameter($field, new \DateTimeImmutable($value), Types::DATETIME_IMMUTABLE);
            } elseif ($field === 'date_to') {
                $qb->andWhere("r.date_time <= :$field")
                   ->setParameter($field, new \DateTimeImmutable($value), Types::DATETIME_IMMUTABLE);
            } elseif (is_bool($value)) {
                $qb->andWhere("r.$field = :$field")
                    ->setParameter($field, $value);
            } else {
                $qb->andWhere("r.$field LIKE :$field")
                    ->setParameter($field, "%$value%");
            }
        }
    }

    /**
     * Apply ordering to the query builder
     * 
     * @param QueryBuilder $qb
     * @param array $orderBy
     */
    private function applyOrderBy(QueryBuilder $qb, array $orderBy): void
    {
        foreach ($orderBy as $field => $direction) {
            $qb->addOrderBy("r.$field", strtoupper($direction));
        }
    }
}
