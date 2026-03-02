<?php

namespace App\Infrastructure\Integration\JSONPlaceholder\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class UserDto
{
    public string $normalizedEmail {
        get => mb_strtolower($this->email) |> trim(...);
    }

    public function __construct(
        public readonly int $id,

        #[Assert\NotBlank]
        public readonly string $name,

        #[Assert\NotBlank]
        public readonly string $username,

        #[Assert\NotBlank]
        #[Assert\Email]
        public readonly string $email,
        public readonly string $phone,
        public readonly string $website,
        public readonly AddressDto $address,
        public readonly CompanyDto $company,
    ) {
    }
}
