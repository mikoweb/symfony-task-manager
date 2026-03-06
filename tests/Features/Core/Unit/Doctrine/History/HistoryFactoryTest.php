<?php

namespace App\Tests\Features\Core\Unit\Doctrine\History;

use App\Core\Doctrine\HistoryType;
use App\Domain\Task\Entity\TaskHistory;
use DateTime;
use UnexpectedValueException;
use stdClass;
use Error;

final class HistoryFactoryTest extends HistoryFactoryTestCase
{
    public function testCreatingWithCorrectData(): void
    {
        $taskId = $this->createUuid();
        $task = $this->createFakeEntity($taskId);
        $userId = $this->createUuid();

        $factory = $this->createCommonFactory(
            forEntity: $task,
            historyEntityClass: TaskHistory::class,
            userId: $userId,
        );

        $changeSet = $this->createChangeSet([
            ['property1', 'old value1', 'new value1'],
            ['property2', 'old value2', 'new value2'],
        ]);

        $history = $factory->create(
            entity: $task,
            type: HistoryType::CREATE,
            changes: $changeSet,
        );

        $this->assertSame($task, $history->getRelatedObject());
        $this->assertEquals($taskId, $history->getRelatedId());
        $this->assertTrue(method_exists($history->getRelatedObject(), 'getId'));
        $this->assertEquals($history->getRelatedId(), $history->getRelatedObject()->getId());
        $this->assertSame(HistoryType::CREATE, $history->getType());
        $this->assertEquals($userId, $history->getChangedById());
        $this->assertEquals($changeSet, $history->getChanges());
        $this->assertGreaterThan(new DateTime()->modify('-1 seconds'), $history->getDate());
    }

    public function testCreatingBrokenHistoryClass(): void
    {
        $task = $this->createFakeEntity($this->createUuid());
        $userId = $this->createUuid();

        $factory = $this->createCommonFactory(
            forEntity: $task,
            // @phpstan-ignore-next-line
            historyEntityClass: 666,
            userId: $userId,
        );

        $this->expectExceptionMessage('History Class `666` not exists');
        $this->expectException(UnexpectedValueException::class);

        $factory->create(
            entity: $task,
            type: HistoryType::CREATE,
            changes: [],
        );
    }

    public function testCreatingForUnsupportedObject(): void
    {
        $task = $this->createFakeEntity($this->createUuid());
        $userId = $this->createUuid();

        $factory = $this->createCommonFactory(
            forEntity: $task,
            historyEntityClass: TaskHistory::class,
            userId: $userId,
        );

        $this->expectExceptionMessage('Entity `stdClass` there is no defined history');
        $this->expectException(UnexpectedValueException::class);

        $factory->create(
            entity: new stdClass(),
            type: HistoryType::CREATE,
            changes: [],
        );
    }

    public function testCreatingWithNoUser(): void
    {
        $task = $this->createFakeEntity($this->createUuid());

        $factory = $this->createCommonFactory(
            forEntity: $task,
            historyEntityClass: TaskHistory::class,
            userId: null,
        );

        $history = $factory->create(
            entity: $task,
            type: HistoryType::CREATE,
            changes: [],
        );

        $this->assertNull($history->getChangedById());
    }

    public function testCreatingInvalidHistoryClass(): void
    {
        $task = $this->createFakeEntity($this->createUuid());
        $userId = $this->createUuid();

        $factory = $this->createCommonFactory(
            forEntity: $task,
            historyEntityClass: DateTime::class,
            userId: $userId,
        );

        $this->expectException(Error::class);

        $factory->create(
            entity: $task,
            type: HistoryType::CREATE,
            changes: [],
        );
    }

    public function testCreatingStringUserId(): void
    {
        $task = $this->createFakeEntity($this->createUuid());

        $factory = $this->createCommonFactory(
            forEntity: $task,
            historyEntityClass: TaskHistory::class,
            userId: '666',
        );

        $history = $factory->create(
            entity: $task,
            type: HistoryType::CREATE,
            changes: [],
        );

        $this->assertNull($history->getChangedById());
    }

    public function testCreatingEmptyChanges(): void
    {
        $task = $this->createFakeEntity($this->createUuid());

        $factory = $this->createCommonFactory(
            forEntity: $task,
            historyEntityClass: TaskHistory::class,
            userId: null,
        );

        $history = $factory->create(
            entity: $task,
            type: HistoryType::CREATE,
            changes: [],
        );

        $this->assertEmpty($history->getChanges());
    }

    public function testCreatingCustomChanges(): void
    {
        $task = $this->createFakeEntity($this->createUuid());

        $factory = $this->createCommonFactory(
            forEntity: $task,
            historyEntityClass: TaskHistory::class,
            userId: null,
        );

        $history = $factory->create(
            entity: $task,
            type: HistoryType::CREATE,
            // @phpstan-ignore-next-line
            changes: ['test'],
        );

        $this->assertEquals(['test'], $history->getChanges());
    }

    public function testEmptyEntityMap(): void
    {
        $factory = $this->createFactory(
            historyEntityMap: [],
            loggedUserProvider: $this->createLoggedUserProvider(null),
        );

        $this->expectExceptionMessage('Entity `DateTime` there is no defined history');
        $this->expectException(UnexpectedValueException::class);

        $factory->create(
            entity: new DateTime(),
            type: HistoryType::CREATE,
            changes: [],
        );
    }

    public function testBadEntityMap(): void
    {
        $factory = $this->createFactory(
            // @phpstan-ignore-next-line
            historyEntityMap: [666],
            loggedUserProvider: $this->createLoggedUserProvider(null),
        );

        $this->expectExceptionMessage('Entity `DateTime` there is no defined history');
        $this->expectException(UnexpectedValueException::class);

        $factory->create(
            entity: new DateTime(),
            type: HistoryType::CREATE,
            changes: [],
        );
    }

    public function testCreatingManyTypes(): void
    {
        $task = $this->createFakeEntity($this->createUuid());

        $factory = $this->createCommonFactory(
            forEntity: $task,
            historyEntityClass: TaskHistory::class,
            userId: null,
        );

        foreach (HistoryType::cases() as $case) {
            $history = $factory->create(
                entity: $task,
                type: $case,
                changes: [],
            );

            $this->assertSame($case, $history->getType());
        }
    }
}
