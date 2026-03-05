<?php

namespace App\Core\Doctrine\History;

use App\Core\Doctrine\Entity\History;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

#[AsDoctrineListener(event: Events::onFlush)]
#[AsDoctrineListener(event: Events::postFlush)]
final class HistoryEntitySubscriber
{
    /**
     * @var History<object>[]
     */
    private array $createdHistory;

    public function __construct(
        /**
         * @var PersistStrategy[]
         */
        #[AutowireIterator(PersistStrategy::class)]
        private readonly iterable $persistStrategies,
        private readonly HistoryEmitter $historyEmitter,
    ) {
        $this->resetCreatedHistory();
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $this->resetCreatedHistory();

        foreach ($this->persistStrategies as $persistStrategy) {
            $persistStrategy->persist($args->getObjectManager(), $args->getObjectManager()->getUnitOfWork());
        }

        foreach ($args->getObjectManager()->getUnitOfWork()->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof History) {
                $this->createdHistory[] = $entity;
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        $createdHistory = $this->createdHistory;
        $this->resetCreatedHistory();

        $uow = $args->getObjectManager()->getUnitOfWork();

        foreach ($createdHistory as $history) {
            if ($uow->getEntityState($history) === UnitOfWork::STATE_MANAGED) {
                $this->historyEmitter->emit($history);
            }
        }
    }

    private function resetCreatedHistory(): void
    {
        $this->createdHistory = [];
    }
}
