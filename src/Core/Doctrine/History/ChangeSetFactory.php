<?php

namespace App\Core\Doctrine\History;

use Doctrine\ORM\Mapping\ClassMetadata;

final readonly class ChangeSetFactory
{
    public function __construct(
        private HistoryNormalizerComposite $normalizerComposite,
    ) {
    }

    /**
     * @param array<string, array{0: mixed, 1: mixed}> $changeSet
     *
     * @return array<string, array{old: mixed, new: mixed}>
     */
    public function create(array $changeSet): array
    {
        return array_map(function ($change) {
            return [
                'old' => $this->normalizerComposite->normalize($change[0] ?? null),
                'new' => $this->normalizerComposite->normalize($change[1] ?? null),
            ];
        }, $changeSet);
    }

    /**
     * @param ClassMetadata<object> $metadata
     *
     * @return array<string, array{old: mixed, new: mixed}>
     */
    public function createFromMetadata(object $entity, ClassMetadata $metadata): array
    {
        $changeSet = [];

        foreach ([...$metadata->getFieldNames(), ...$metadata->getAssociationNames()] as $field) {
            $changeSet[$field] = [
                'old' => $metadata->getFieldValue($entity, $field),
                'new' => null,
            ];
        }

        return $changeSet;
    }
}
