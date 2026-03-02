<?php

namespace App\Infrastructure\User\Repository;

use App\Domain\User\AddressRepository;
use App\Domain\User\Entity\Address;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Address>
 */
final class ORMAddressRepository extends ServiceEntityRepository implements AddressRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }
}
