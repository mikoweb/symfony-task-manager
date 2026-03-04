<?php

namespace App\Domain\Task\Entity;

use App\Core\Doctrine\Entity\History;
use App\Domain\User\Entity\User;
use App\Infrastructure\Task\Repository\ORMTaskHistoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * @extends History<User>
 *
 * @method User getRelatedObject()
 */
#[ORM\Entity(repositoryClass: ORMTaskHistoryRepository::class)]
#[ORM\Table(name: 'task_history')]
class TaskHistory extends History
{
    /**
     * @var User|null
     */
    #[ORM\JoinColumn(name: 'related_object_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[ORM\ManyToOne(targetEntity: User::class)]
    protected ?object $relatedObject;

    #[ORM\Column(name: 'related_id', type: UuidType::NAME, index: true)]
    protected mixed $relatedId;
}
