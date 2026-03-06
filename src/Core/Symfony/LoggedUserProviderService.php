<?php

namespace App\Core\Symfony;

use App\Domain\User\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

final readonly class LoggedUserProviderService implements LoggedUserProvider
{
    public function __construct(
        private Security $security,
    ) {
    }

    public function getLoggedUser(): UserInterface|User|null
    {
        return $this->security->getUser();
    }

    public function getLoggedUserId(): ?Uuid
    {
        if (is_null($this->getLoggedUser())) {
            return null;
        }

        $user = $this->getLoggedUser();

        if (!method_exists($user, 'getId') || !$user->getId() instanceof Uuid) {
            return null;
        }

        return $user->getId();
    }
}
