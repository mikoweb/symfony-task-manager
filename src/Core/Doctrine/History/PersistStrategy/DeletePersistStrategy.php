<?php

namespace App\Core\Doctrine\History\PersistStrategy;

use App\Core\Doctrine\History\ChangeSetFactory;
use App\Core\Doctrine\History\HistoryFactory;
use App\Core\Doctrine\History\PersistStrategy;
use App\Core\Doctrine\HistoryType;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\ObjectManager;

final readonly class DeletePersistStrategy implements PersistStrategy
{
    public function __construct(
        private ChangeSetFactory $changeSetFactory,
        private HistoryFactory $historyFactory,
    ) {
    }

    public function persist(ObjectManager $em, UnitOfWork $uow): void
    {
        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($this->historyFactory->supports($entity)) {
                $history = $this->historyFactory->create(
                    $entity,
                    HistoryType::DELETE,
                    $this->changeSetFactory->createFromMetadata(
                        $entity,
                        // @phpstan-ignore-next-line
                        $em->getClassMetadata(ClassUtils::getRealClass($entity::class))
                    ),
                );

                $em->persist($history);
                // @phpstan-ignore-next-line
                $uow->computeChangeSet($em->getClassMetadata(ClassUtils::getRealClass($history::class)), $history);
            }
        }
    }
}
