<?php

namespace App\Core\Doctrine\History;

use Doctrine\Common\Collections\Collection;

final class HistoryNormalizerComposite implements HistoryNormalizer
{
    private const string UNSUPPORTED_TAG = '[UNSUPPORTED_DATA]';

    /**
     * @var HistoryNormalizer[]
     */
    private array $normalizers;

    public function __construct()
    {
        $this->normalizers = [];
    }

    public function add(HistoryNormalizer $normalizer): void
    {
        $this->normalizers[] = $normalizer;
    }

    public function supports(mixed $value): bool
    {
        return $value instanceof Collection
            || array_any($this->normalizers, fn (HistoryNormalizer $normalizer): bool => $normalizer->supports($value));
    }

    public function normalize(mixed $value): mixed
    {
        if ($value instanceof Collection) {
            return $value->map(fn ($item) => $this->normalize($item))->toArray();
        }

        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supports($value)) {
                return $normalizer->normalize($value);
            }
        }

        return self::UNSUPPORTED_TAG;
    }
}
