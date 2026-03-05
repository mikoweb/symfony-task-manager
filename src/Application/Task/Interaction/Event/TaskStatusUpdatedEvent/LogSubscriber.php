<?php

namespace App\Application\Task\Interaction\Event\TaskStatusUpdatedEvent;

use App\Application\Task\Log\TaskEventLogger;
use App\Core\Bus\BusChoice;
use App\Domain\Task\Event\TaskStatusUpdatedEvent;
use App\Domain\Task\TaskRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use UnexpectedValueException;

final readonly class LogSubscriber
{
    public function __construct(
        private TaskRepository $taskRepository,
        private TaskEventLogger $taskEventLogger,
    ) {
    }

    #[AsMessageHandler(bus: BusChoice::EVENT)]
    public function handle(TaskStatusUpdatedEvent $event): void
    {
        $task = $this->taskRepository->find($event->taskId);

        if (is_null($task)) {
            throw new UnexpectedValueException(sprintf('Task with id `%s` not found.', $event->taskId->toString()));
        }

        $this->taskEventLogger->log(
            $task,
            $event::class,
            sprintf(
                'Status in Task `%s` has been changed from `%s` to `%s`.',
                $task->getName(),
                $event->oldStatus->getLabel(),
                $event->newStatus->getLabel(),
            ),
        );
    }
}
