<?php

namespace App\Application\User\Interaction\Command\ImportUsersFromJSONPlaceholder;

use App\Core\Mapper\NestedEntityMapper;
use App\Domain\User\Entity\Address;
use App\Domain\User\Entity\Company;
use App\Domain\User\Entity\User;
use App\Domain\User\UserRepository;
use App\Infrastructure\Integration\JSONPlaceholder\Dto\UserDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

final readonly class UserImporter
{
    public function __construct(
        private UserRepository $userRepository,
        private ObjectMapperInterface $objectMapper,
        private NestedEntityMapper $nestedEntityMapper,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function import(UserDto $dto, bool $flush = false): User
    {
        $user = $this->userRepository->findOneBy(['email' => $dto->normalizedEmail])
            ?? $this->objectMapper->map($dto, User::class);

        $this->mapUserData($user, $dto);
        $this->entityManager->persist($user);

        if ($flush) {
            $this->entityManager->flush();
        }

        return $user;
    }

    private function mapUserData(User $user, UserDto $dto): void
    {
        $this->nestedEntityMapper->map($user, $dto, 'getAddress', 'setAddress', Address::class, 'address');
        $this->nestedEntityMapper->map($user, $dto, 'getCompany', 'setCompany', Company::class, 'company');

        $user->getAddress()
            ?->setLat($dto->address->geo->lat)
            ?->setLng($dto->address->geo->lng)
        ;
    }
}
