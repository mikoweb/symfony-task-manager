<?php

namespace App\Core\Doctrine\History;

use App\Core\Doctrine\Entity\History;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface HistoryObserver
{
    /**
     * @param History<object> $history
     */
    public function supports(History $history): bool;

    /**
     * @param History<object> $history
     */
    public function update(History $history): void;
}
