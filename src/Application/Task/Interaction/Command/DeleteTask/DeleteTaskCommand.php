<?php

namespace App\Application\Task\Interaction\Command\DeleteTask;

use App\Core\Interaction\Command\Command;
use App\Core\Symfony\Validator\EntityExist;
use App\Domain\Task\Entity\Task;
use Symfony\Component\Uid\Uuid;

final readonly class DeleteTaskCommand implements Command
{
    public function __construct(
        #[EntityExist(entity: Task::class)]
        public Uuid $taskId,
    ) {
    }
}
