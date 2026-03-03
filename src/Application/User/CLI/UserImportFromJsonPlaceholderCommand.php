<?php

namespace App\Application\User\CLI;

use App\Application\User\Interaction\Command\ImportUsersFromJSONPlaceholder\ImportUsersFromJSONPlaceholderCommand;
use App\Core\Bus\CommandBus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:user:import-from-json-placeholder',
    description: 'Import users from JSONPlaceholder',
)]
final class UserImportFromJsonPlaceholderCommand extends Command
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->commandBus->dispatch(new ImportUsersFromJSONPlaceholderCommand());

        $io->success('Users were imported from JSONPlaceholder');

        return Command::SUCCESS;
    }
}
