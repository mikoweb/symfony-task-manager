<?php

namespace App\Core\Bus;

use App\Core\Interaction\Event\Event;

interface EventBus
{
    public function dispatch(Event $event, ?StampCollection $stamps = null): void;
}
