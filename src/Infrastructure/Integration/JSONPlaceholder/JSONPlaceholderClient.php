<?php

namespace App\Infrastructure\Integration\JSONPlaceholder;

interface JSONPlaceholderClient
{
    /**
     * @param array<string, mixed> $options
     *
     * @return object[]|object
     */
    public function request(string $method, string $path, array $options = []): array|object;
}
