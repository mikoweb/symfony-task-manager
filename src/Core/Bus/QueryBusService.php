<?php

namespace App\Core\Bus;

use App\Core\Interaction\Query\Query;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

readonly class QueryBusService implements QueryBus
{
    public function __construct(
        private MessageBusInterface $queryBus,
    ) {
    }

    public function dispatch(Query $query, ?StampCollection $stamps = null): mixed
    {
        $envelope = $this->queryBus->dispatch($query, is_null($stamps) ? [] : $stamps->toArray());

        return $envelope->last(HandledStamp::class)?->getResult();
    }
}
