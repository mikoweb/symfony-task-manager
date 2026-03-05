<?php

namespace App\Infrastructure\Task\Repository;

use App\Domain\Task\Entity\TaskEventLog;
use App\Domain\Task\TaskEventLogRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TaskEventLog>
 */
final class ORMTaskEventLogRepository extends ServiceEntityRepository implements TaskEventLogRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaskEventLog::class);
    }
}
