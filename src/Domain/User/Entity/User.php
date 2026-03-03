<?php

namespace App\Domain\User\Entity;

use App\Core\Doctrine\Entity\Timestampable;
use App\Core\Doctrine\Entity\TimestampableTrait;
use App\Domain\User\UserRoleName;
use App\Infrastructure\User\Repository\ORMUserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ORMUserRepository::class)]
#[ORM\Table(name: 'users')]
#[ORM\UniqueConstraint(name: 'users_email_unique', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface, Timestampable
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private Uuid $id;

    #[ORM\Column(name: 'email', type: Types::STRING)]
    private string $email;

    /**
     * @var string[]
     */
    #[ORM\Column(name: 'roles', type: Types::JSON)]
    private array $roles = [];

    #[ORM\Column(name: 'password', type: Types::STRING, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(name: 'name', type: Types::STRING, nullable: true)]
    private ?string $name;

    #[ORM\Column(name: 'username', type: Types::STRING, nullable: true)]
    private ?string $username;

    #[ORM\Column(name: 'phone', type: Types::STRING, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(name: 'website', type: Types::STRING, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(name: 'json_placeholder_id', type: Types::INTEGER, unique: true, nullable: true)]
    private ?int $jsonPlaceholderId = null;

    #[ORM\ManyToOne(targetEntity: Address::class, cascade: ['persist'], inversedBy: 'users')]
    #[ORM\JoinColumn(name: 'address_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Address $address = null;

    #[ORM\ManyToOne(targetEntity: Company::class, cascade: ['persist'], inversedBy: 'users')]
    #[ORM\JoinColumn(name: 'company_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Company $company = null;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        // @phpstan-ignore-next-line
        return $this->email;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return array_merge($this->roles, [UserRoleName::ROLE_USER]) |> array_unique(...);
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getJsonPlaceholderId(): ?int
    {
        return $this->jsonPlaceholderId;
    }

    public function setJsonPlaceholderId(?int $jsonPlaceholderId): self
    {
        $this->jsonPlaceholderId = $jsonPlaceholderId;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }
}
