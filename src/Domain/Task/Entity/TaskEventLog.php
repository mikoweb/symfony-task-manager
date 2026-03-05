<?php

namespace App\Domain\Task\Entity;

use App\Core\Doctrine\Entity\Timestampable;
use App\Core\Doctrine\Entity\TimestampableTrait;
use App\Infrastructure\Task\Repository\ORMTaskEventLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ORMTaskEventLogRepository::class)]
#[ORM\Table(name: 'task_event_logs')]
class TaskEventLog implements Timestampable
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private Uuid $id;

    #[ORM\JoinColumn(name: 'task_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[ORM\ManyToOne(targetEntity: Task::class)]
    private ?Task $task;

    #[ORM\Column(name: 'event_name', type: Types::STRING)]
    private string $eventName;

    #[ORM\Column(name: 'message', type: Types::STRING, length: 500)]
    private string $message;

    public function __construct(
        ?Task $task,
        string $eventName,
        string $message,
    ) {
        $this->task = $task;
        $this->eventName = $eventName;
        $this->message = $message;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
