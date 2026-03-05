<?php

namespace App\Domain\Task\Event;

use App\Core\Interaction\Event\Event;
use Symfony\Component\Uid\Uuid;

final readonly class TaskCreatedEvent implements Event
{
    public function __construct(
        public Uuid $taskId,
    ) {
    }
}
