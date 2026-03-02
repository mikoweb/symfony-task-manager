<?php

namespace App\Core\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;

/**
 * @template T of object
 *
 * @extends ObjectRepository<T>
 */
interface UniversalRepository extends ObjectRepository
{
    public function createQueryBuilder(string $alias, ?string $indexBy = null): QueryBuilder;
}
