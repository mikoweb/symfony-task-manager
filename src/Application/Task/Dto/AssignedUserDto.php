<?php

namespace App\Application\Task\Dto;

final readonly class AssignedUserDto
{
    public function __construct(
        public string $id,
        public ?string $name,
        public ?string $nickname,
    ) {
    }
}
