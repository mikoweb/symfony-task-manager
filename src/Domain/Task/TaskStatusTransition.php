<?php

namespace App\Domain\Task;

enum TaskStatusTransition: string
{
    case START = 'start';
    case COMPLETE = 'complete';
    case REOPEN = 'reopen';
    case ABANDON = 'abandon';
}
