<?php

namespace App\Domain\Task\Entity;

use App\Core\Doctrine\Entity\Timestampable;
use App\Core\Doctrine\Entity\TimestampableTrait;
use App\Domain\Task\TaskStatus;
use App\Domain\User\Entity\User;
use App\Infrastructure\Task\Repository\ORMTaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ORMTaskRepository::class)]
#[ORM\Table(name: 'tasks')]
class Task implements Timestampable
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private Uuid $id;

    #[ORM\Column(name: 'name', type: Types::STRING)]
    private string $name;

    #[ORM\Column(name: 'description', type: Types::TEXT)]
    private string $description;

    #[ORM\Column(name: 'status', type: Types::SMALLINT, enumType: TaskStatus::class)]
    private TaskStatus $status = TaskStatus::TO_DO;

    public string $workflowStatus {
        get => $this->status->getLabel();
        set(string $status) {
            $this->status = TaskStatus::fromName($status);
        }
    }

    #[ORM\JoinColumn(name: 'assigned_user_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $assignedUser = null;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    public function setStatus(TaskStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAssignedUser(): ?User
    {
        return $this->assignedUser;
    }

    public function setAssignedUser(?User $assignedUser): self
    {
        $this->assignedUser = $assignedUser;

        return $this;
    }
}
