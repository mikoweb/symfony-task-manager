<?php

namespace App\Infrastructure\Integration\JSONPlaceholder;

use App\Infrastructure\Integration\JSONPlaceholder\Dto\UserDto;
use App\Infrastructure\Integration\JSONPlaceholder\Factory\UserDtoFactory;
use Symfony\Component\HttpFoundation\Request;

readonly class JSONUserRepositoryService implements JSONUserRepository
{
    public function __construct(
        private JSONPlaceholderClient $client,
        private UserDtoFactory $userDtoFactory,
    ) {
    }

    /**
     * @return UserDto[]
     */
    public function findAll(): array
    {
        /** @var object[] $users */
        $users = $this->client->request(Request::METHOD_GET, '/users');

        return array_map($this->userDtoFactory->create(...), $users);
    }
}
