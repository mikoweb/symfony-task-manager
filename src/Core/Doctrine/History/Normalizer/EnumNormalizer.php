<?php

namespace App\Core\Doctrine\History\Normalizer;

use App\Core\Doctrine\History\HistoryNormalizer;
use BackedEnum;

final class EnumNormalizer implements HistoryNormalizer
{
    public function supports(mixed $value): bool
    {
        return $value instanceof BackedEnum;
    }

    /**
     * @param BackedEnum $value
     */
    public function normalize(mixed $value): mixed
    {
        return $value->value;
    }
}
