<?php

namespace App\Application\Task\Interaction\Command\CreateTask;

use App\Application\Task\Dto\CreateTaskDto;
use App\Core\Interaction\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateTaskCommand implements Command
{
    public function __construct(
        #[Assert\Valid]
        public CreateTaskDto $dto,
    ) {
    }
}
