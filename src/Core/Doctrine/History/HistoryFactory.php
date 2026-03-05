<?php

namespace App\Core\Doctrine\History;

use App\Core\Doctrine\Entity\History;
use App\Core\Doctrine\HistoryType;

interface HistoryFactory
{
    public function supports(object $entity): bool;

    /**
     * @param array<string, array{old: mixed, new: mixed}> $changes
     *
     * @return History<object>
     */
    public function create(
        object $entity,
        HistoryType $type,
        array $changes,
    ): History;
}
