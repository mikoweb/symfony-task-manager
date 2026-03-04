<?php

namespace App\Application\Task\Mapper;

use App\Application\Task\Dto\CreateTaskDto;
use App\Application\Task\Dto\UpdateTaskDto;
use App\Domain\Task\Entity\Task;
use App\Domain\User\UserRepository;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

final readonly class TaskMapper
{
    public function __construct(
        private ObjectMapperInterface $objectMapper,
        private UserRepository $userRepository,
    ) {
    }

    public function map(CreateTaskDto|UpdateTaskDto $dto, ?Task $task = null): Task
    {
        if (is_null($task)) {
            $task = $this->objectMapper->map($dto, Task::class);
        } else {
            $this->objectMapper->map($dto, $task);
        }

        if (!is_null($dto->assignedUserId)) {
            $task->setAssignedUser($this->userRepository->find($dto->assignedUserId));
        }

        return $task;
    }
}
