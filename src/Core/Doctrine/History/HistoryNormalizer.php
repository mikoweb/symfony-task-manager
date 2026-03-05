<?php

namespace App\Core\Doctrine\History;

interface HistoryNormalizer
{
    public function supports(mixed $value): bool;
    public function normalize(mixed $value): mixed;
}
