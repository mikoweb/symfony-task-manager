<?php

namespace App\Application\User\Interaction\Command\ConfigureUserPassword;

use App\Core\Interaction\Command\Command;
use App\Core\Symfony\Validator\EntityExist;
use App\Domain\User\Entity\User;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ConfigureUserPasswordCommand implements Command
{
    public function __construct(
        #[EntityExist(User::class)]
        public Uuid $id,

        #[Assert\NotBlank]
        #[Assert\PasswordStrength(
            minScore: Assert\PasswordStrength::STRENGTH_WEAK,
        )]
        public string $password,
    ) {
    }
}
