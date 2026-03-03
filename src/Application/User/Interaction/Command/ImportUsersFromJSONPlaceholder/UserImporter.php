<?php

namespace App\Application\User\Interaction\Command\ImportUsersFromJSONPlaceholder;

use App\Domain\User\Entity\User;
use App\Domain\User\UserRepository;
use App\Infrastructure\Integration\JSONPlaceholder\Dto\UserDto;
use Doctrine\ORM\EntityManagerInterface;

final readonly class UserImporter
{
    public function __construct(
        private UserRepository $userRepository,
        private UserMapper $userMapper,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function import(UserDto $dto, bool $flush = false): User
    {
        $user = $this->userMapper->map($dto, $this->userRepository->findOneBy(['email' => $dto->normalizedEmail]));
        $this->entityManager->persist($user);

        if ($flush) {
            $this->entityManager->flush();
        }

        return $user;
    }
}
