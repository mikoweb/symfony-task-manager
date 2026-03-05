<?php

namespace App\Infrastructure\Task\Query\Decorator;

use App\Infrastructure\Task\Query\TaskQueryFactory;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

final readonly class HistoryTaskQueryDecorator implements TaskQueryFactory
{
    public function __construct(
        private Uuid $taskId,
        private TaskQueryFactory $taskQuery,
    ) {
    }

    public function create(): QueryBuilder
    {
        return $this->taskQuery->create()
            ->andWhere('h.relatedId = :history_task_id')
            ->setParameter('history_task_id', $this->taskId, UuidType::NAME)
        ;
    }
}
