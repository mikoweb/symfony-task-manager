<?php

namespace App\Core\Doctrine;

use Doctrine\ORM\QueryBuilder;

interface QueryBuilderFactory
{
    /**
     * @param class-string<object> $entityClass
     */
    public function create(string $entityClass, string $alias, ?string $indexBy = null): QueryBuilder;
}
