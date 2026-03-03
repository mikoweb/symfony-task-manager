<?php

namespace App\Domain\User\Entity;

use App\Core\Doctrine\Entity\Timestampable;
use App\Core\Doctrine\Entity\TimestampableTrait;
use App\Infrastructure\User\Repository\ORMCompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ORMCompanyRepository::class)]
#[ORM\Table(name: 'companies')]
class Company implements Timestampable
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[Groups(['list'])]
    private Uuid $id;

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['list'])]
    private string $name;

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['list'])]
    private string $catchPhrase;

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['list'])]
    private string $bs;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'company')]
    private Collection $users;

    public function __construct(
        string $name,
        string $catchPhrase,
        string $bs,
    ) {
        $this->name = $name;
        $this->catchPhrase = $catchPhrase;
        $this->bs = $bs;
        $this->users = new ArrayCollection();
    }

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

    public function getCatchPhrase(): string
    {
        return $this->catchPhrase;
    }

    public function setCatchPhrase(string $catchPhrase): self
    {
        $this->catchPhrase = $catchPhrase;

        return $this;
    }

    public function getBs(): string
    {
        return $this->bs;
    }

    public function setBs(string $bs): self
    {
        $this->bs = $bs;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }
}
