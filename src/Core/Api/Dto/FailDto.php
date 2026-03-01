<?php

namespace App\Core\Api\Dto;

final readonly class FailDto
{
    public const string COMMON_FAIL_MESSAGE = 'Something went wrong...';

    public function __construct(
        public string $message = self::COMMON_FAIL_MESSAGE,
    ) {
    }
}
