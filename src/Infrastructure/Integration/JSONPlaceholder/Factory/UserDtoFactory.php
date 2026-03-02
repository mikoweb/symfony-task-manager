<?php

namespace App\Infrastructure\Integration\JSONPlaceholder\Factory;

use App\Infrastructure\Integration\JSONPlaceholder\Dto\UserDto;

interface UserDtoFactory
{
    public function create(object $user): UserDto;
}
