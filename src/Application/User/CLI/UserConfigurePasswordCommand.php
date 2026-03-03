<?php

namespace App\Application\User\CLI;

use App\Application\User\Interaction\Command\ConfigureUserPassword\ConfigureUserPasswordCommand;
use App\Core\Bus\CommandBus;
use App\Domain\User\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

#[AsCommand(
    name: 'app:user:configure-password',
    description: 'Configure password for a user',
)]
class UserConfigurePasswordCommand extends Command
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
            ->addArgument('password', InputArgument::REQUIRED, 'User Password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        /** @var string $email */
        $email = $input->getArgument('email');
        /** @var string $password */
        $password = $input->getArgument('password');

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (is_null($user)) {
            $io->error(sprintf('User with email `%s` not found.', $email));

            return Command::FAILURE;
        }

        try {
            $this->commandBus->dispatch(new ConfigureUserPasswordCommand($user->getId(), $password));
        } catch (ValidationFailedException $exception) {
            $io->error($exception->getViolations()->get(0)->getMessage());

            return Command::FAILURE;
        }

        $io->success(sprintf('User `%s` password configured ', $email));

        return Command::SUCCESS;
    }
}
