<?php

namespace App\Infrastructure\Task\Query;

use App\Core\Doctrine\QueryBuilderFactory;
use App\Domain\Task\Entity\Task;
use Doctrine\ORM\QueryBuilder;

final readonly class ListTaskQueryFactory implements TaskQueryFactory
{
    public function __construct(
        private QueryBuilderFactory $qbf,
    ) {
    }

    public function create(): QueryBuilder
    {
        return $this->qbf->create(Task::class, 't')
            ->select('t, au')
            ->leftJoin('t.assignedUser', 'au')
        ;
    }
}
