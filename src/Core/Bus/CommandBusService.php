<?php

namespace App\Core\Bus;

use App\Core\Interaction\Command\Command;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

readonly class CommandBusService implements CommandBus
{
    public function __construct(
        private MessageBusInterface $commandBus,
    ) {
    }

    public function dispatch(Command $command, ?StampCollection $stamps = null): mixed
    {
        $envelope = $this->commandBus->dispatch($command, is_null($stamps) ? [] : $stamps->toArray());

        return $envelope->last(HandledStamp::class)?->getResult();
    }
}
