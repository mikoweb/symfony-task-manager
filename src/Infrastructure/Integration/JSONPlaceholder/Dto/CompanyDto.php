<?php

namespace App\Infrastructure\Integration\JSONPlaceholder\Dto;

final readonly class CompanyDto
{
    public function __construct(
        public string $name,
        public string $catchPhrase,
        public string $bs,
    ) {
    }
}
