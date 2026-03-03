<?php

namespace App\Application\User\CLI;

use App\Application\User\Interaction\Command\ConfigureUserRoles\ConfigureUserRolesCommand;
use App\Core\Bus\CommandBus;
use App\Domain\User\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:user:configure-roles',
    description: 'Configure roles for users',
)]
class UserConfigureRolesCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'User Email')
            ->addArgument('roles', InputArgument::IS_ARRAY, 'User Roles Array')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        /** @var string $email */
        $email = $input->getArgument('email');
        /** @var string[] $roles */
        $roles = $input->getArgument('roles');

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (is_null($user)) {
            $io->error(sprintf('User with email `%s` not found.', $email));

            return Command::FAILURE;
        }

        $this->commandBus->dispatch(new ConfigureUserRolesCommand($user->getId(), $roles));

        $io->success(sprintf('The following roles have been set for user `%s`: [%s]', $email, implode(', ', $roles)));

        return Command::SUCCESS;
    }
}
