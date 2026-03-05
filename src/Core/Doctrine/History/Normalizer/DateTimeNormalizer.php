<?php

namespace App\Core\Doctrine\History\Normalizer;

use App\Core\Doctrine\History\HistoryNormalizer;
use DateTimeInterface;

final class DateTimeNormalizer implements HistoryNormalizer
{
    public function supports(mixed $value): bool
    {
        return $value instanceof DateTimeInterface;
    }

    /**
     * @param DateTimeInterface $value
     *
     * @return string
     */
    public function normalize(mixed $value): mixed
    {
        return $value->format(DateTimeInterface::ATOM);
    }
}
