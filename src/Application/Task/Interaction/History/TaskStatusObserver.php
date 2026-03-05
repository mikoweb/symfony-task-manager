<?php

namespace App\Application\Task\Interaction\History;

use App\Core\Bus\EventBus;
use App\Core\Doctrine\Entity\History;
use App\Core\Doctrine\History\HistoryObserver;
use App\Core\Doctrine\HistoryType;
use App\Domain\Task\Entity\Task;
use App\Domain\Task\Entity\TaskHistory;
use App\Domain\Task\Event\TaskStatusUpdatedEvent;
use App\Domain\Task\TaskStatus;

final readonly class TaskStatusObserver implements HistoryObserver
{
    private const string STATUS_FIELD = 'status';

    public function __construct(
        private EventBus $eventBus,
    ) {
    }

    public function supports(History $history): bool
    {
        return $history->testType(HistoryType::UPDATE) && $history->testRelatedType(Task::class);
    }

    /**
     * @param TaskHistory $history
     */
    public function update(History $history): void
    {
        if ($history->isFieldChanged(self::STATUS_FIELD)) {
            /** @var array{old: int, new: int} $change */
            $change = $history->getChangedField(self::STATUS_FIELD);

            $this->eventBus->dispatch(
                new TaskStatusUpdatedEvent(
                    taskId: $history->getRelatedObject()->getId(),
                    oldStatus: TaskStatus::from($change['old']),
                    newStatus: TaskStatus::from($change['new']),
                ),
            );
        }
    }
}
