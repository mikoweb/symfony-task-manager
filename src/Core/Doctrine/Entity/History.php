<?php

namespace App\Core\Doctrine\Entity;

use App\Core\Doctrine\HistoryType;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

/**
 * @template T of object
 */
#[ORM\MappedSuperclass]
abstract class History
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected Uuid $id;

    /**
     * @var T|null
     */
    protected ?object $relatedObject;
    protected mixed $relatedId;

    #[ORM\Column(name: 'type', type: Types::STRING, length: 10, enumType: HistoryType::class)]
    protected HistoryType $type;

    #[ORM\Column(name: 'date', type: Types::DATETIME_IMMUTABLE)]
    protected DateTimeImmutable $date;

    /**
     * @var array<string, mixed>
     */
    #[ORM\Column(name: 'changes', type: Types::JSON)]
    protected array $changes;

    #[ORM\Column(name: 'changed_by_id', type: UuidType::NAME, index: true)]
    protected ?Uuid $changedById;

    /**
     * @param T|null               $related
     * @param array<string, mixed> $changes
     */
    public function __construct(
        ?object $related,
        mixed $relatedId,
        HistoryType $type,
        DateTimeImmutable $date,
        array $changes,
    ) {
        $this->relatedObject = $related;
        $this->relatedId = $relatedId;
        $this->type = $type;
        $this->date = $date;
        $this->changes = $changes;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return T|null
     */
    public function getRelatedObject(): ?object
    {
        return $this->relatedObject;
    }

    public function getRelatedId(): mixed
    {
        return $this->relatedId;
    }

    public function getType(): HistoryType
    {
        return $this->type;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return array<string, mixed>
     */
    public function getChanges(): array
    {
        return $this->changes;
    }

    public function getChangedById(): ?Uuid
    {
        return $this->changedById;
    }
}
