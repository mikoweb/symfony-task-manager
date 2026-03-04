<?php

namespace App\Infrastructure\Task\Repository;

use App\Domain\Task\Entity\TaskHistory;
use App\Domain\Task\TaskHistoryRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TaskHistory>
 */
final class ORMTaskHistoryRepository extends ServiceEntityRepository implements TaskHistoryRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaskHistory::class);
    }
}
