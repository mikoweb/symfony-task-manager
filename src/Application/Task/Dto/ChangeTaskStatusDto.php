<?php

namespace App\Application\Task\Dto;

use App\Domain\Task\TaskStatusTransition;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ChangeTaskStatusDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(callback: [TaskStatusTransition::class, 'getStringCases'])]
        public string $transitionName,
    ) {
    }
}
