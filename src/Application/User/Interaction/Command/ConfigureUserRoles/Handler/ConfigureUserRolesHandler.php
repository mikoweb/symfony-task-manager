<?php

namespace App\Application\User\Interaction\Command\ConfigureUserRoles\Handler;

use App\Application\User\Interaction\Command\ConfigureUserRoles\ConfigureUserRolesCommand;
use App\Core\Bus\BusChoice;
use App\Domain\User\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use LogicException;

final readonly class ConfigureUserRolesHandler
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    #[AsMessageHandler(bus: BusChoice::COMMAND)]
    public function handle(ConfigureUserRolesCommand $command): void
    {
        $user = $this->userRepository->find($command->id);

        if (is_null($user)) {
            throw new LogicException(sprintf('User with id `%s` not found', $command->id->toString()));
        }

        $user->setRoles($command->roles);
    }
}
