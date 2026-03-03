<?php

namespace App\Application\User\Dto;

final readonly class AddressDto
{
    public function __construct(
        public string $id,
        public string $street,
        public string $suite,
        public string $city,
        public string $zipcode,
        public ?float $lat,
        public ?float $lng,
    ) {
    }
}
