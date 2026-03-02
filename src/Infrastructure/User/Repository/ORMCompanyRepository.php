<?php

namespace App\Infrastructure\User\Repository;

use App\Domain\User\CompanyRepository;
use App\Domain\User\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Company>
 */
final class ORMCompanyRepository extends ServiceEntityRepository implements CompanyRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }
}
