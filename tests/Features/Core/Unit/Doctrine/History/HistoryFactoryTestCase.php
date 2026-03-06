<?php

namespace App\Tests\Features\Core\Unit\Doctrine\History;

use App\Core\Doctrine\History\HistoryEntityMap;
use App\Core\Doctrine\History\HistoryFactory;
use App\Core\Doctrine\History\HistoryFactoryService;
use App\Core\Symfony\LoggedUserProvider;
use App\Domain\User\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

abstract class HistoryFactoryTestCase extends TestCase
{
    /**
     * @param array<string, mixed> $historyEntityMap
     */
    protected function createFactory(
        array $historyEntityMap,
        LoggedUserProvider $loggedUserProvider,
    ): HistoryFactory {
        return new HistoryFactoryService(
            $this->createHistoryEntityMap($historyEntityMap),
            $loggedUserProvider,
        );
    }

    /**
     * @param class-string $historyEntityClass
     */
    protected function createCommonFactory(
        object $forEntity,
        string $historyEntityClass,
        mixed $userId,
    ): HistoryFactory {
        return $this->createFactory(
            historyEntityMap: [
                $forEntity::class => $historyEntityClass,
            ],
            loggedUserProvider: $this->createLoggedUserProvider(
                $this->createFakeUser($userId),
            ),
        );
    }

    protected function createLoggedUserProvider(?UserInterface $user): LoggedUserProvider
    {
        return new readonly class ($user) implements LoggedUserProvider {
            public function __construct(
                private ?UserInterface $user,
            ) {
            }

            public function getLoggedUser(): UserInterface|User|null
            {
                return $this->user;
            }

            public function getLoggedUserId(): ?Uuid
            {
                $id = !is_null($this->user) && method_exists($this->user, 'getId')
                    ? $this->user->getId()
                    : null;

                return $id instanceof Uuid ? $id : null;
            }
        };
    }

    /**
     * @param array<string, mixed> $map
     */
    protected function createHistoryEntityMap(array $map): HistoryEntityMap
    {
        return new HistoryEntityMap($map);
    }

    protected function createUuid(): Uuid
    {
        return Uuid::v7();
    }

    protected function createFakeUser(mixed $id): UserInterface
    {
        return new readonly class ($id) implements UserInterface {
            public function __construct(
                private mixed $id,
            ) {
            }

            public function getId(): mixed
            {
                return $this->id;
            }

            public function getRoles(): array
            {
                return [];
            }

            public function eraseCredentials(): void
            {
            }

            public function getUserIdentifier(): string
            {
                return uniqid();
            }
        };
    }

    protected function createFakeEntity(mixed $id): object
    {
        return new readonly class ($id) {
            public function __construct(
                private mixed $id,
            ) {
            }

            public function getId(): mixed
            {
                return $this->id;
            }
        };
    }

    /**
     * @param array<int, mixed[]> $items
     *
     * @return array<string, array{old: mixed, new: mixed}>
     */
    protected function createChangeSet(array $items): array
    {
        $changeSet = [];

        foreach ($items as $item) {
            if (isset($item[0])) {
                /** @var string $key */
                $key = $item[0];
                $changeSet[$key] = [
                    'old' => $item[1] ?? null,
                    'new' => $item[2] ?? null,
                ];
            }
        }

        return $changeSet;
    }
}
