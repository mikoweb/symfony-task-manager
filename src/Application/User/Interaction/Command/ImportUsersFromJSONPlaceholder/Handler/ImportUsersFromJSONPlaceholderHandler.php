<?php

namespace App\Application\User\Interaction\Command\ImportUsersFromJSONPlaceholder\Handler;

use App\Application\User\Interaction\Command\ImportUsersFromJSONPlaceholder\ImportUsersFromJSONPlaceholderCommand;
use App\Core\Bus\BusChoice;
use App\Domain\User\Entity\Address;
use App\Domain\User\Entity\Company;
use App\Domain\User\Entity\User;
use App\Domain\User\UserRepository;
use App\Infrastructure\Integration\JSONPlaceholder\Dto\UserDto;
use App\Infrastructure\Integration\JSONPlaceholder\JSONUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class ImportUsersFromJSONPlaceholderHandler
{
    public function __construct(
        private JSONUserRepository $jsonUserRepository,
        private UserRepository $userRepository,
        private ValidatorInterface $validator,
        private ObjectMapperInterface $objectMapper,
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[AsMessageHandler(bus: BusChoice::COMMAND)]
    public function handle(ImportUsersFromJSONPlaceholderCommand $command): void
    {
        $users = $this->jsonUserRepository->findAll();
        $errors = $this->validator->validate($users);

        if ($errors->count() > 0) {
            throw new ValidationFailedException($command, $errors);
        }

        foreach ($users as $user) {
            $this->importUser($user);
        }
    }

    private function importUser(UserDto $dto): void
    {
        $user = $this->userRepository->findOneBy(['email' => $dto->normalizedEmail])
            ?? $this->objectMapper->map($dto, User::class);

        $this->mapUserData($user, $dto);
        $this->entityManager->persist($user);
    }

    private function mapUserData(User $user, UserDto $dto): void
    {
        if (is_null($user->getAddress())) {
            $address = $this->objectMapper->map($dto->address, Address::class);
            $user->setAddress($address);
        } else {
            $this->objectMapper->map($dto->address, $user->getAddress());
        }

        $user->getAddress()
            ?->setLat($dto->address->geo->lat)
            ?->setLng($dto->address->geo->lng)
        ;

        if (is_null($user->getCompany())) {
            $company = $this->objectMapper->map($dto->company, Company::class);
            $user->setCompany($company);
        } else {
            $this->objectMapper->map($dto->company, $user->getCompany());
        }
    }
}
