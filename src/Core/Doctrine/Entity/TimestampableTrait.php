<?php

namespace App\Core\Doctrine\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Groups;

trait TimestampableTrait
{
    #[Groups(['timestampable', 'list'])]
    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE, nullable: true)]
    protected ?DateTimeImmutable $createdAt;

    #[Groups(['timestampable', 'list'])]
    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(name: 'updated_at', type: Types::DATETIME_IMMUTABLE, nullable: true)]
    protected ?DateTimeImmutable $updatedAt;

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
