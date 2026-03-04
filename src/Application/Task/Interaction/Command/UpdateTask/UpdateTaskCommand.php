<?php

namespace App\Application\Task\Interaction\Command\UpdateTask;

use App\Application\Task\Dto\UpdateTaskDto;
use App\Core\Interaction\Command\Command;
use App\Core\Symfony\Validator\EntityExist;
use App\Domain\Task\Entity\Task;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateTaskCommand implements Command
{
    public function __construct(
        #[EntityExist(entity: Task::class)]
        public Uuid $taskId,

        #[Assert\Valid]
        public UpdateTaskDto $dto,
    ) {
    }
}
