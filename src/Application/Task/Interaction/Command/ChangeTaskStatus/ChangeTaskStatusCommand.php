<?php

namespace App\Application\Task\Interaction\Command\ChangeTaskStatus;

use App\Application\Task\Dto\ChangeTaskStatusDto;
use App\Core\Interaction\Command\Command;
use App\Core\Symfony\Validator\EntityExist;
use App\Domain\Task\Entity\Task;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ChangeTaskStatusCommand implements Command
{
    public function __construct(
        #[EntityExist(entity: Task::class)]
        public Uuid $taskId,

        #[Assert\Valid]
        public ChangeTaskStatusDto $dto,
    ) {
    }
}
