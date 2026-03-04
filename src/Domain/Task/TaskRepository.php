<?php

namespace App\Domain\Task;

use App\Core\Repository\UniversalRepository;
use App\Domain\Task\Entity\Task;

/**
 * @extends UniversalRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array<string, mixed> $criteria, array<string, string> $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array<string, mixed> $criteria, array<string, string> $orderBy = null, $limit = null, $offset = null)
 */
interface TaskRepository extends UniversalRepository
{
}
