<?php

namespace App\Application\User\Interaction\Command\ImportUsersFromJSONPlaceholder\Handler;

use App\Application\User\Interaction\Command\ImportUsersFromJSONPlaceholder\ImportUsersFromJSONPlaceholderCommand;
use App\Application\User\Interaction\Command\ImportUsersFromJSONPlaceholder\UserImporter;
use App\Core\Bus\BusChoice;
use App\Infrastructure\Integration\JSONPlaceholder\JSONUserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class ImportUsersFromJSONPlaceholderHandler
{
    public function __construct(
        private JSONUserRepository $jsonUserRepository,
        private ValidatorInterface $validator,
        private UserImporter $userImporter,
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
            $this->userImporter->import($user);
        }
    }
}
