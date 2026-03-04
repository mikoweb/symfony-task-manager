<?php

namespace App\Domain\Task;

enum TaskStatusTransition: string
{
    case START = 'start';
    case COMPLETE = 'complete';
    case REOPEN = 'reopen';
    case ABANDON = 'abandon';

    /**
     * @return string[]
     */
    public static function getStringCases(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
