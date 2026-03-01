<?php

namespace App\Core\Bus;

use App\Core\Interaction\Query\Query;

interface QueryBus
{
    public function dispatch(Query $query, ?StampCollection $stamps = null): mixed;
}
