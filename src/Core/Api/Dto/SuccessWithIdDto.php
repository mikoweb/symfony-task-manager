<?php

namespace App\Core\Api\Dto;

final readonly class SuccessWithIdDto extends SuccessDto
{
    public function __construct(
        string $message,
        public int|string $id,
    ) {
        parent::__construct($message);
    }
}
