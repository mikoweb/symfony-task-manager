<?php

namespace App\Core\Bus;

final class BusChoice
{
    public const string COMMAND = 'command_bus';
    public const string EVENT = 'event_bus';
    public const string QUERY = 'query_bus';
}
