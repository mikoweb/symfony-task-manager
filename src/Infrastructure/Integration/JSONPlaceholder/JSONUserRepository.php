<?php

namespace App\Infrastructure\Integration\JSONPlaceholder;

use App\Infrastructure\Integration\JSONPlaceholder\Dto\UserDto;

interface JSONUserRepository
{
    /**
     * @return UserDto[]
     */
    public function findAll(): array;
}
