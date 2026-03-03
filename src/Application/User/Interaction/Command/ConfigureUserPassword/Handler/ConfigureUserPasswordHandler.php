<?php

namespace App\Application\User\Interaction\Command\ConfigureUserPassword\Handler;

use App\Application\User\Interaction\Command\ConfigureUserPassword\ConfigureUserPasswordCommand;
use App\Core\Bus\BusChoice;
use App\Domain\User\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use LogicException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class ConfigureUserPasswordHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    #[AsMessageHandler(bus: BusChoice::COMMAND)]
    public function handle(ConfigureUserPasswordCommand $command): void
    {
        $user = $this->userRepository->find($command->id);

        if (is_null($user)) {
            throw new LogicException(sprintf('User with id `%s` not found', $command->id->toString()));
        }

        $user->setPassword($this->passwordHasher->hashPassword($user, $command->password));
    }
}
