<?php

namespace App\Application\Task\Dto;

use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

final readonly class TaskHistoryDto
{
    /**
     * @param array<string, array{old: mixed, new: mixed}> $changes
     */
    public function __construct(
        public Uuid $id,
        public string $type,
        public DateTimeImmutable $date,
        public array $changes,
        public ?Uuid $changedById,
    ) {
    }
}
