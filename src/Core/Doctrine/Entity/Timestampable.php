<?php

namespace App\Core\Doctrine\Entity;

use DateTimeImmutable;

interface Timestampable
{
    public function getCreatedAt(): ?DateTimeImmutable;
    public function setCreatedAt(?DateTimeImmutable $createdAt): self;
    public function getUpdatedAt(): ?DateTimeImmutable;
    public function setUpdatedAt(?DateTimeImmutable $updatedAt): self;
}
