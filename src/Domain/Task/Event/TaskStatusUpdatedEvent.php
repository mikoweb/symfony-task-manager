<?php

namespace App\Domain\Task\Event;

use App\Core\Interaction\Event\Event;
use App\Domain\Task\TaskStatus;
use Symfony\Component\Uid\Uuid;

final readonly class TaskStatusUpdatedEvent implements Event
{
    public function __construct(
        public Uuid $taskId,
        public TaskStatus $oldStatus,
        public TaskStatus $newStatus,
    ) {
    }
}
