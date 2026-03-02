<?php

namespace App\Infrastructure\Integration\JSONPlaceholder\Factory;

use App\Core\Mapper\ObjectMapper;
use App\Infrastructure\Integration\JSONPlaceholder\Dto\UserDto;

final readonly class UserDtoFactoryService implements UserDtoFactory
{
    public function __construct(
        private ObjectMapper $objectMapper,
    ) {
    }

    public function create(object $user): UserDto
    {
        /** @var UserDto $dto */
        $dto = $this->objectMapper->map($user, UserDto::class);

        return $dto;
    }
}
