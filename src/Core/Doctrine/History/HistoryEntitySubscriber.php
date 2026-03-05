<?php

namespace App\Core\Doctrine\History;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

#[AsDoctrineListener(event: Events::onFlush)]
final readonly class HistoryEntitySubscriber
{
    public function __construct(
        /**
         * @var PersistStrategy[]
         */
        #[AutowireIterator(PersistStrategy::class)]
        private iterable $persistStrategies = [],
    ) {
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        foreach ($this->persistStrategies as $persistStrategy) {
            $persistStrategy->persist($args->getObjectManager(), $args->getObjectManager()->getUnitOfWork());
        }
    }
}
