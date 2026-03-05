<?php

namespace App\Infrastructure\Task\Logger;

use App\Application\Task\Log\TaskEventLogger;
use App\Domain\Task\Entity\Task;
use App\Domain\Task\Entity\TaskEventLog;
use Doctrine\ORM\EntityManagerInterface;

final readonly class ORMTaskEventLogger implements TaskEventLogger
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function log(Task $task, string $eventName, string $message): TaskEventLog
    {
        $log = new TaskEventLog($task, $eventName, $message);
        $this->entityManager->persist($log);
        $this->entityManager->flush();

        return $log;
    }
}
