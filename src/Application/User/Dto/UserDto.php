<?php

namespace App\Application\User\Dto;

final readonly class UserDto
{
    public function __construct(
        public string $id,
        public string $email,
        public ?string $name,
        public ?string $nickname,
        public ?string $phone,
        public ?string $website,
        public ?AddressDto $address,
        public ?CompanyDto $company,

        /**
         * @var string[]
         */
        public array $roles,
    ) {
    }
}
