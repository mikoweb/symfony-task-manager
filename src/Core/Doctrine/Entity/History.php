<?php

namespace App\Core\Doctrine\Entity;

use App\Core\Doctrine\HistoryType;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;
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

    #[Groups(['list'])]
    #[SerializedName('id')]
    protected mixed $relatedId;

    #[ORM\Column(name: 'type', type: Types::STRING, length: 10, enumType: HistoryType::class)]
    #[Groups(['list'])]
    protected HistoryType $type;

    #[ORM\Column(name: 'date', type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['list'])]
    protected DateTimeImmutable $date;

    /**
     * @var array<string, array{old: mixed, new: mixed}>
     */
    #[ORM\Column(name: 'changes', type: Types::JSON)]
    #[Groups(['list'])]
    protected array $changes;

    #[ORM\Column(name: 'changed_by_id', type: UuidType::NAME, nullable: true, index: true)]
    #[Groups(['list'])]
    protected ?Uuid $changedById;

    /**
     * @param T|null                                       $related
     * @param array<string, array{old: mixed, new: mixed}> $changes
     */
    public function __construct(
        ?object $related,
        mixed $relatedId,
        HistoryType $type,
        DateTimeImmutable $date,
        array $changes,
        ?Uuid $changedById,
    ) {
        $this->relatedObject = $related;
        $this->relatedId = $relatedId;
        $this->type = $type;
        $this->date = $date;
        $this->changes = $changes;
        $this->changedById = $changedById;
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

    /**
     * @param class-string $className
     */
    public function testRelatedType(string $className): bool
    {
        return $this->getRelatedObject() instanceof $className;
    }

    public function getType(): HistoryType
    {
        return $this->type;
    }

    public function testType(HistoryType $type): bool
    {
        return $this->getType() === $type;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return array<string, array{old: mixed, new: mixed}>
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
