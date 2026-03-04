<?php

namespace App\Core\Doctrine;

enum HistoryType: string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
}
