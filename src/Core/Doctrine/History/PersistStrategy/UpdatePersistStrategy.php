<?php

namespace App\Core\Doctrine\History\PersistStrategy;

use App\Core\Doctrine\History\ChangeSetFactory;
use App\Core\Doctrine\History\HistoryFactory;
use App\Core\Doctrine\History\PersistStrategy;
use App\Core\Doctrine\HistoryType;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\ObjectManager;

final readonly class UpdatePersistStrategy implements PersistStrategy
{
    public function __construct(
        private ChangeSetFactory $changeSetFactory,
        private HistoryFactory $historyFactory,
    ) {
    }

    public function persist(ObjectManager $em, UnitOfWork $uow): void
    {
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($this->historyFactory->supports($entity)) {
                /** @var array<string, array{0: mixed, 1: mixed}> $changeSet */
                $changeSet = $uow->getEntityChangeSet($entity);

                $history = $this->historyFactory->create(
                    $entity,
                    HistoryType::UPDATE,
                    $this->changeSetFactory->create($changeSet),
                );

                $em->persist($history);
                // @phpstan-ignore-next-line
                $uow->computeChangeSet($em->getClassMetadata(ClassUtils::getRealClass($history::class)), $history);
            }
        }
    }
}
