<?php

namespace App\Core\Bus;

use App\Core\Interaction\Command\Command;

interface CommandBus
{
    public function dispatch(Command $command, ?StampCollection $stamps = null): mixed;
}
