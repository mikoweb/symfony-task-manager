<?php

namespace App\Application\Task\Dto;

use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

final readonly class TaskDetailsDto
{
    public function __construct(
        public Uuid $id,
        public string $name,
        public string $description,
        public string $workflowStatus,
        public ?AssignedUserDto $assignedUser,
        public ?DateTimeImmutable $createdAt,
        public ?DateTimeImmutable $updatedAt,
    ) {
    }
}
