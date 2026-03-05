<?php

namespace App\Core\Doctrine\History\Normalizer;

use App\Core\Doctrine\History\HistoryNormalizer;

final class NullNormalizer implements HistoryNormalizer
{
    public function supports(mixed $value): bool
    {
        return is_null($value);
    }

    public function normalize(mixed $value): mixed
    {
        return $value;
    }
}
