<?php

namespace App\Application\User\Dto;

final readonly class CompanyDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $catchPhrase,
        public string $bs,
    ) {
    }
}
