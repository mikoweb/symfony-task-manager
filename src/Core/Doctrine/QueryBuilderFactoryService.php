<?php

namespace App\Core\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

final readonly class QueryBuilderFactoryService implements QueryBuilderFactory
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function create(string $entityClass, string $alias, ?string $indexBy = null): QueryBuilder
    {
        return $this->entityManager->getRepository($entityClass)->createQueryBuilder($alias, $indexBy);
    }
}
