<?php

namespace App\Application\Task\Interaction\Command\UpdateTask\Handler;

use App\Application\Task\Interaction\Command\UpdateTask\UpdateTaskCommand;
use App\Application\Task\Mapper\TaskMapper;
use App\Core\Bus\BusChoice;
use App\Domain\Task\TaskRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use LogicException;

final readonly class UpdateTaskHandler
{
    public function __construct(
        private TaskRepository $taskRepository,
        private TaskMapper $taskMapper,
    ) {
    }

    #[AsMessageHandler(bus: BusChoice::COMMAND)]
    public function handle(UpdateTaskCommand $command): void
    {
        $task = $this->taskRepository->find($command->taskId);

        if (is_null($task)) {
            throw new LogicException(sprintf('Task with id `%s` not found', $command->taskId->toString()));
        }

        $this->taskMapper->map($command->dto, $task);
    }
}
