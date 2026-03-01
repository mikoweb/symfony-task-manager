<?php

namespace App\Core\Api\Dto;

readonly class SuccessDto
{
    public function __construct(
        public string $message,
    ) {
    }
}
