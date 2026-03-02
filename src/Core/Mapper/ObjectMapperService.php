<?php

namespace App\Core\Mapper;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final readonly class ObjectMapperService implements ObjectMapper
{
    public function __construct(
        private DenormalizerInterface $denormalizer,
    ) {
    }

    /**
     * @param object|mixed[]     $data
     * @param array<string>|null $groups
     */
    public function map(object|array $data, string $targetClass, ?array $groups = null): object
    {
        /** @var object $object */
        $object = $this->denormalizer->denormalize(
            $data,
            $targetClass,
            context: is_null($groups) ? [] : ['groups' => $groups],
        );

        return $object;
    }

    /**
     * @param array<mixed[]|object> $source
     * @param array<string>|null    $groups
     *
     * @return object[]
     */
    public function mapAny(array $source, string $targetClass, ?array $groups = null): array
    {
        return array_map(
            fn (object|array $data): object => $this->map($data, $targetClass, $groups),
            $source,
        );
    }
}
