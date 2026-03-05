<?php

namespace App\Core\Doctrine\History;

use App\Core\Doctrine\Entity\History;
use App\Core\Doctrine\HistoryType;
use App\Domain\User\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Uuid;
use UnexpectedValueException;

final readonly class HistoryFactoryService implements HistoryFactory
{
    public function __construct(
        private HistoryEntityMap $historyEntityMap,
        private Security $security,
    ) {
    }

    public function supports(object $entity): bool
    {
        $class = ClassUtils::getRealClass($entity::class);

        return $this->historyEntityMap->hasEntity($class) && !is_null($this->historyEntityMap->getHistoryClass($class));
    }

    /**
     * @param array<string, array{old: mixed, new: mixed}> $changes
     */
    public function create(object $entity, HistoryType $type, array $changes): History
    {
        $entityClass = ClassUtils::getRealClass($entity::class);
        /** @var class-string<History<object>>|null $historyClass */
        $historyClass = $this->historyEntityMap->getHistoryClass($entityClass);

        if (is_null($historyClass)) {
            throw new UnexpectedValueException(sprintf('class %s does not support history', $entityClass));
        }

        return new $historyClass(
            related: $type === HistoryType::DELETE ? null : $entity,
            relatedId: $this->getEntityId($entity) ?? $this->getEntityIdFromChangeSet($changes),
            type: $type,
            date: new DateTimeImmutable(),
            changes: $changes,
            changedById: $this->security->getUser() instanceof User ? $this->security->getUser()->getId() : null,
        );
    }

    private function getEntityId(object $entity): mixed
    {
        if (method_exists($entity, 'getId')) {
            /** @var Uuid|mixed $id */
            $id = $entity->getId();

            return $id instanceof Uuid ? (clone $id) : (is_scalar($id) ? $id : null);
        }

        return null;
    }

    /**
     * @param array<string, array{old: mixed, new: mixed}> $changeSet
     */
    private function getEntityIdFromChangeSet(array $changeSet): mixed
    {
        if (!isset($changeSet['id'])) {
            return null;
        }

        $id = $changeSet['id']['new'] ?? $changeSet['id']['old'] ?? null;

        return is_string($id) && Uuid::isValid($id) ? Uuid::fromString($id) : $id;
    }
}
