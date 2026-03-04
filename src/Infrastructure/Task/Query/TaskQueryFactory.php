<?php

namespace App\Infrastructure\Task\Query;

use Doctrine\ORM\QueryBuilder;

interface TaskQueryFactory
{
    public function create(): QueryBuilder;
}
