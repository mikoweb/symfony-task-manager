<?php

namespace App\Domain\User;

use App\Core\Repository\UniversalRepository;
use App\Domain\User\Entity\User;

/**
 * @extends UniversalRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array<string, mixed> $criteria, array<string, string> $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array<string, mixed> $criteria, array<string, string> $orderBy = null, $limit = null, $offset = null)
 */
interface UserRepository extends UniversalRepository
{
}
