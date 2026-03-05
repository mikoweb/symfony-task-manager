<?php

namespace App\Core\Doctrine\History\Normalizer;

use App\Core\Doctrine\History\HistoryNormalizer;
use Symfony\Component\Uid\Uuid;

final class ObjectNormalizer implements HistoryNormalizer
{
    public function supports(mixed $value): bool
    {
        return is_object($value);
    }

    /**
     * @param object $value
     */
    public function normalize(mixed $value): mixed
    {
        if (method_exists($value, 'getId')) {
            /** @var Uuid|mixed $id */
            $id = $value->getId();

            return $id instanceof Uuid ? $id->toString() : (is_scalar($id) ? $id : null);
        } elseif (method_exists($value, 'toString')) {
            return $value->toString();
        } elseif (method_exists($value, '__toString')) {
            return (string) $value;
        }

        return $value::class;
    }
}
