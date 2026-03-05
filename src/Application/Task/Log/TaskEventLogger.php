<?php

namespace App\Application\Task\Log;

use App\Domain\Task\Entity\Task;
use App\Domain\Task\Entity\TaskEventLog;

interface TaskEventLogger
{
    public function log(Task $task, string $eventName, string $message): TaskEventLog;
}
