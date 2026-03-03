<?php

namespace App\Application\User\Interaction\Command\ConfigureUserRoles;

use App\Core\Interaction\Command\Command;
use App\Core\Symfony\Validator\EntityExist;
use App\Domain\User\Entity\User;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ConfigureUserRolesCommand implements Command
{
    public function __construct(
        #[EntityExist(User::class)]
        public Uuid $id,

        /**
         * @var string[]
         */
        #[Assert\All([
            new Assert\NotBlank(),
            new Assert\Type('string'),
        ])]
        public array $roles,
    ) {
    }
}
