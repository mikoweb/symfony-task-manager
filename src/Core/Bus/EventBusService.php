<?php

namespace App\Core\Bus;

use App\Core\Interaction\Event\Event;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class EventBusService implements EventBus
{
    public function __construct(
        private MessageBusInterface $eventBus,
    ) {
    }

    public function dispatch(Event $event, ?StampCollection $stamps = null): void
    {
        $this->eventBus->dispatch($event, is_null($stamps) ? [] : $stamps->toArray());
    }
}
