<?php

namespace App\Core\Mapper;

interface NestedEntityMapper
{
    public function map(
        object $targetEntity,
        object $sourceDto,
        string $targetGetter,
        string $targetSetter,
        string $targetEntityClass,
        string $sourceProperty,
    ): void;
}
