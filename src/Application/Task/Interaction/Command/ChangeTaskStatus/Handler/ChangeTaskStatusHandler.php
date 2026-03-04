<?php

namespace App\Application\Task\Interaction\Command\ChangeTaskStatus\Handler;

use App\Application\Task\Interaction\Command\ChangeTaskStatus\ChangeTaskStatusCommand;
use App\Core\Bus\BusChoice;
use App\Domain\Task\TaskRepository;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use DomainException;
use LogicException;
use Symfony\Component\Workflow\WorkflowInterface;

final readonly class ChangeTaskStatusHandler
{
    public function __construct(
        private TaskRepository $taskRepository,
        #[Target('task_status')]
        private WorkflowInterface $statusWorkflow,
    ) {
    }

    #[AsMessageHandler(bus: BusChoice::COMMAND)]
    public function handle(ChangeTaskStatusCommand $command): void
    {
        $task = $this->taskRepository->find($command->taskId);

        if (is_null($task)) {
            throw new LogicException(sprintf('Task with id `%s` not found', $command->taskId->toString()));
        }

        if (!$this->statusWorkflow->can($task, $command->dto->transitionName)) {
            throw new DomainException(sprintf('Status transition `%s` is currently not possible', $command->dto->transitionName));
        }

        $this->statusWorkflow->apply($task, $command->dto->transitionName);
    }
}
