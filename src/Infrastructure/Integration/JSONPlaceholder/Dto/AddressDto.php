<?php

namespace App\Infrastructure\Integration\JSONPlaceholder\Dto;

final readonly class AddressDto
{
    public function __construct(
        public string $street,
        public string $suite,
        public string $city,
        public string $zipcode,
        public GeoDto $geo,
    ) {
    }
}
