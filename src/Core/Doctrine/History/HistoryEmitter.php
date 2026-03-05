<?php

namespace App\Core\Doctrine\History;

use App\Core\Doctrine\Entity\History;

interface HistoryEmitter
{
    /**
     * @param History<object> $history
     */
    public function emit(History $history): void;
}
