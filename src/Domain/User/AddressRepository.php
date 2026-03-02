<?php

namespace App\Domain\User;

use App\Core\Repository\UniversalRepository;
use App\Domain\User\Entity\Address;

/**
 * @extends UniversalRepository<Address>
 *
 * @method Address|null find($id, $lockMode = null, $lockVersion = null)
 * @method Address|null findOneBy(array<string, mixed> $criteria, array<string, string> $orderBy = null)
 * @method Address[]    findAll()
 * @method Address[]    findBy(array<string, mixed> $criteria, array<string, string> $orderBy = null, $limit = null, $offset = null)
 */
interface AddressRepository extends UniversalRepository
{
}
