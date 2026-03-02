<?php

namespace App\Core\Mapper;

interface ObjectMapper
{
    /**
     * @param object|mixed[]     $data
     * @param array<string>|null $groups
     */
    public function map(array|object $data, string $targetClass, ?array $groups = null): object;

    /**
     * @param array<mixed[]|object> $source
     * @param array<string>|null    $groups
     *
     * @return object[]
     */
    public function mapAny(array $source, string $targetClass, ?array $groups = null): array;
}
