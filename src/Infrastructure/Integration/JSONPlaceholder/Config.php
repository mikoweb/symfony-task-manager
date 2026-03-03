<?php

namespace App\Infrastructure\Integration\JSONPlaceholder;

final readonly class Config
{
    public function __construct(
        public string $baseUri,
    ) {
    }
}
