<?php

namespace App\Core\Symfony;

use App\Domain\User\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

interface LoggedUserProvider
{
    public function getLoggedUser(): UserInterface|User|null;
    public function getLoggedUserId(): ?Uuid;
}
