<?php

namespace App\Domain\Task;

use function Symfony\Component\String\u;

enum TaskStatus: int
{
    case TO_DO = 100;
    case IN_PROGRESS = 200;
    case DONE = 300;

    public function getLabel(): string
    {
        return u($this->name)->lower()->toString();
    }

    public static function fromName(string $name): self
    {
        /** @var self $status */
        $status = self::{u($name)->upper()->toString()};

        return $status;
    }
}
