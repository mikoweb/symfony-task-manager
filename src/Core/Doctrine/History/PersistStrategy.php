<?php

namespace App\Core\Doctrine\History;

use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface PersistStrategy
{
    public function persist(ObjectManager $em, UnitOfWork $uow): void;
}
