<?php

namespace App\Core\Doctrine\History;

use UnexpectedValueException;

final readonly class HistoryEntityMap
{
    /**
     * @param array<string, string|null> $map
     */
    public function __construct(
        private array $map,
    ) {
    }

    /**
     * @param class-string $entityClass
     */
    public function hasEntity(string $entityClass): bool
    {
        return array_key_exists($entityClass, $this->map);
    }

    /**
     * @param class-string $entityClass
     */
    public function getHistoryClass(string $entityClass): ?string
    {
        if (!$this->hasEntity($entityClass)) {
            throw new UnexpectedValueException(sprintf('Entity `%s` there is no defined history.', $entityClass));
        }

        if (is_string($this->map[$entityClass]) || is_null($this->map[$entityClass])) {
            return $this->map[$entityClass];
        }

        throw new UnexpectedValueException('Unsupported History Entity class');
    }
}
