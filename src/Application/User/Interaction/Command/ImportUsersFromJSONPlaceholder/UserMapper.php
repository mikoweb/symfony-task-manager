<?php

namespace App\Application\User\Interaction\Command\ImportUsersFromJSONPlaceholder;

use App\Core\Mapper\NestedEntityMapper;
use App\Domain\User\Entity\Address;
use App\Domain\User\Entity\Company;
use App\Domain\User\Entity\User;
use App\Infrastructure\Integration\JSONPlaceholder\Dto\UserDto;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

final readonly class UserMapper
{
    public function __construct(
        private ObjectMapperInterface $objectMapper,
        private NestedEntityMapper $nestedEntityMapper,
    ) {
    }

    public function map(UserDto $dto, ?User $user = null): User
    {
        if (is_null($user)) {
            $user = $this->objectMapper->map($dto, User::class);
        } else {
            $this->objectMapper->map($dto, $user);
        }

        $this->nestedEntityMapper->map($user, $dto, 'getAddress', 'setAddress', Address::class, 'address');
        $this->nestedEntityMapper->map($user, $dto, 'getCompany', 'setCompany', Company::class, 'company');

        $user->getAddress()
            ?->setLat($dto->address->geo->lat)
            ?->setLng($dto->address->geo->lng)
        ;

        return $user;
    }
}
