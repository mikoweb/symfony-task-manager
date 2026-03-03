<?php

namespace App\Infrastructure\Integration\JSONPlaceholder\Dto;

use App\Domain\User\Entity\User;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

#[Map(target: User::class)]
final class UserDto
{
    #[Map(target: 'email')]
    public string $normalizedEmail {
        get => mb_strtolower($this->email) |> trim(...);
    }

    public function __construct(
        #[Map(target: 'jsonPlaceholderId')]
        public readonly int $id,

        #[Assert\NotBlank]
        public readonly string $name,

        #[Assert\NotBlank]
        public readonly string $username,

        #[Assert\NotBlank]
        #[Assert\Email]
        #[Map(target: 'email', if: false)]
        public readonly string $email,
        public readonly string $phone,
        public readonly string $website,

        #[Map(target: 'address', if: false)]
        public readonly AddressDto $address,

        #[Map(target: 'company', if: false)]
        public readonly CompanyDto $company,
    ) {
    }
}
