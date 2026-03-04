<?php

namespace App\Domain\Task;

use App\Core\Repository\UniversalRepository;
use App\Domain\Task\Entity\TaskHistory;

/**
 * @extends UniversalRepository<TaskHistory>
 *
 * @method TaskHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaskHistory|null findOneBy(array<string, mixed> $criteria, array<string, string> $orderBy = null)
 * @method TaskHistory[]    findAll()
 * @method TaskHistory[]    findBy(array<string, mixed> $criteria, array<string, string> $orderBy = null, $limit = null, $offset = null)
 */
interface TaskHistoryRepository extends UniversalRepository
{
}
