<?php

namespace App\Core\Mapper;

use Symfony\Component\ObjectMapper\ObjectMapperInterface;

final readonly class NestedEntityMapperService implements NestedEntityMapper
{
    public function __construct(
        private ObjectMapperInterface $objectMapper,
    ) {
    }

    /**
     * @param class-string<object> $targetEntityClass
     */
    public function map(
        object $targetEntity,
        object $sourceDto,
        string $targetGetter,
        string $targetSetter,
        string $targetEntityClass,
        string $sourceProperty,
    ): void {
        /** @var object $sourceObject */
        $sourceObject = $sourceDto->{$sourceProperty};
        /** @var object|null $nestedEntity */
        $nestedEntity = $targetEntity->{$targetGetter}();

        if (is_null($nestedEntity)) {
            $entity = $this->objectMapper->map($sourceObject, $targetEntityClass);
            $targetEntity->{$targetSetter}($entity);
        } else {
            $this->objectMapper->map($sourceObject, $nestedEntity);
        }
    }
}
