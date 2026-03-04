<?php

namespace App\Infrastructure\Task\Query\Decorator;

use App\Domain\User\Entity\User;
use App\Infrastructure\Task\Query\TaskQueryFactory;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Types\UuidType;

final readonly class UserTaskQueryDecorator implements TaskQueryFactory
{
    public function __construct(
        private User $user,
        private TaskQueryFactory $taskQuery,
    ) {
    }

    public function create(): QueryBuilder
    {
        return $this->taskQuery->create()
            ->andWhere('au.id = :assigned_user_id')
            ->setParameter('assigned_user_id', $this->user->getId(), UuidType::NAME)
        ;
    }
}
