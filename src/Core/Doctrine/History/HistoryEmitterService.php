<?php

namespace App\Core\Doctrine\History;

use App\Core\Doctrine\Entity\History;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

final readonly class HistoryEmitterService implements HistoryEmitter
{
    public function __construct(
        /**
         * @var HistoryObserver[]
         */
        #[AutowireIterator(HistoryObserver::class)]
        private iterable $observers,
    ) {
    }

    /**
     * @param History<object> $history
     */
    public function emit(History $history): void
    {
        foreach ($this->observers as $observer) {
            if ($observer->supports($history)) {
                $observer->update($history);
            }
        }
    }
}
