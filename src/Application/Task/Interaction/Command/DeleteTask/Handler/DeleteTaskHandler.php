<?php

namespace App\Application\Task\Interaction\Command\DeleteTask\Handler;

use App\Application\Task\Interaction\Command\DeleteTask\DeleteTaskCommand;
use App\Core\Bus\BusChoice;
use App\Domain\Task\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use LogicException;

final readonly class DeleteTaskHandler
{
    public function __construct(
        private TaskRepository $taskRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[AsMessageHandler(bus: BusChoice::COMMAND)]
    public function handle(DeleteTaskCommand $command): void
    {
        $task = $this->taskRepository->find($command->taskId);

        if (is_null($task)) {
            throw new LogicException(sprintf('Task with id `%s` not found', $command->taskId->toString()));
        }

        $this->entityManager->remove($task);
    }
}
