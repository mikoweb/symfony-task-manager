<?php

namespace App\Domain\Task;

use App\Core\Repository\UniversalRepository;
use App\Domain\Task\Entity\TaskEventLog;

/**
 * @extends UniversalRepository<TaskEventLog>
 *
 * @method TaskEventLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaskEventLog|null findOneBy(array<string, mixed> $criteria, array<string, string> $orderBy = null)
 * @method TaskEventLog[]    findAll()
 * @method TaskEventLog[]    findBy(array<string, mixed> $criteria, array<string, string> $orderBy = null, $limit = null, $offset = null)
 */
interface TaskEventLogRepository extends UniversalRepository
{
}
