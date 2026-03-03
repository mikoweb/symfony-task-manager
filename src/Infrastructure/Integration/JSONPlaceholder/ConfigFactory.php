<?php

namespace App\Infrastructure\Integration\JSONPlaceholder;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final readonly class ConfigFactory
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
    ) {
    }

    public function create(): Config
    {
        return new Config(
            baseUri: $this->parameterBag->get('json_placeholder_base_uri'),
        );
    }
}
