<?php

namespace App\Application\Task\Interaction\Command\CreateTask\Handler;

use App\Application\Task\Interaction\Command\CreateTask\CreateTaskCommand;
use App\Application\Task\Mapper\TaskMapper;
use App\Core\Bus\BusChoice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

final readonly class CreateTaskHandler
{
    public function __construct(
        private TaskMapper $taskMapper,
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[AsMessageHandler(bus: BusChoice::COMMAND)]
    public function handle(CreateTaskCommand $command): Uuid
    {
        $task = $this->taskMapper->map($command->dto);
        $this->entityManager->persist($task);

        return $task->getId();
    }
}
