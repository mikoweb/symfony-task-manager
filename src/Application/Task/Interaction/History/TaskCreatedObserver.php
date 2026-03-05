<?php

namespace App\Application\Task\Interaction\History;

use App\Core\Bus\EventBus;
use App\Core\Doctrine\Entity\History;
use App\Core\Doctrine\History\HistoryObserver;
use App\Core\Doctrine\HistoryType;
use App\Domain\Task\Entity\Task;
use App\Domain\Task\Entity\TaskHistory;
use App\Domain\Task\Event\TaskCreatedEvent;

final readonly class TaskCreatedObserver implements HistoryObserver
{
    public function __construct(
        private EventBus $eventBus,
    ) {
    }

    public function supports(History $history): bool
    {
        return $history->testType(HistoryType::CREATE) && $history->testRelatedType(Task::class);
    }

    /**
     * @param TaskHistory $history
     */
    public function update(History $history): void
    {
        $this->eventBus->dispatch(new TaskCreatedEvent($history->getRelatedObject()->getId()));
    }
}
