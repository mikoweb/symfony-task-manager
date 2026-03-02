<?php

namespace App\Infrastructure\Integration\JSONPlaceholder\Dto;

final readonly class GeoDto
{
    public float $lat;
    public float $lng;

    public function __construct(float|string $lat, float|string $lng)
    {
        $this->lat = (float) $lat;
        $this->lng = (float) $lng;
    }
}
