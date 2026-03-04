<?php

namespace App\Application\Task\Dto;

use App\Core\Symfony\Validator\EntityExist;
use App\Domain\Task\Entity\Task;
use App\Domain\User\Entity\User;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[Map(target: Task::class)]
final readonly class UpdateTaskDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $name,
        public string $description,

        #[EntityExist(entity: User::class)]
        public ?Uuid $assignedUserId,
    ) {
    }
}
