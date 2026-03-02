<?php

namespace App\Domain\User;

use App\Core\Repository\UniversalRepository;
use App\Domain\User\Entity\Company;

/**
 * @extends UniversalRepository<Company>
 *
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array<string, mixed> $criteria, array<string, string> $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array<string, mixed> $criteria, array<string, string> $orderBy = null, $limit = null, $offset = null)
 */
interface CompanyRepository extends UniversalRepository
{
}
