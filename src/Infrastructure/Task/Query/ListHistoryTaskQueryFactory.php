<?php

namespace App\Infrastructure\Task\Query;

use App\Core\Doctrine\QueryBuilderFactory;
use App\Domain\Task\Entity\TaskHistory;
use Doctrine\ORM\QueryBuilder;

final readonly class ListHistoryTaskQueryFactory implements TaskQueryFactory
{
    public function __construct(
        private QueryBuilderFactory $qbf,
    ) {
    }

    public function create(): QueryBuilder
    {
        return $this->qbf->create(TaskHistory::class, 'h')
            ->select('h')
        ;
    }
}
