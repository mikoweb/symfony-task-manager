<?php

namespace App\Domain\User\Entity;

use App\Core\Doctrine\Entity\Timestampable;
use App\Core\Doctrine\Entity\TimestampableTrait;
use App\Infrastructure\User\Repository\ORMAddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ORMAddressRepository::class)]
#[ORM\Table(name: 'addresses')]
class Address implements Timestampable
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private Uuid $id;

    #[ORM\Column(type: Types::STRING)]
    private string $street;

    #[ORM\Column(type: Types::STRING)]
    private string $suite;

    #[ORM\Column(type: Types::STRING)]
    private string $city;

    #[ORM\Column(type: Types::STRING)]
    private string $zipcode;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $lat = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $lng = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'address')]
    private Collection $users;

    public function __construct(
        string $street,
        string $suite,
        string $city,
        string $zipcode,
    ) {
        $this->street = $street;
        $this->suite = $suite;
        $this->city = $city;
        $this->zipcode = $zipcode;
        $this->users = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getSuite(): string
    {
        return $this->suite;
    }

    public function setSuite(string $suite): self
    {
        $this->suite = $suite;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZipcode(): string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(?float $lng): self
    {
        $this->lng = $lng;

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
