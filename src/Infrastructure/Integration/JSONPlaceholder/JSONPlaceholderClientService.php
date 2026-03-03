<?php

namespace App\Infrastructure\Integration\JSONPlaceholder;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\HttpOptions;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class JSONPlaceholderClientService implements JSONPlaceholderClient
{
    private ?HttpClientInterface $client = null;

    public function __construct(
        private readonly ConfigFactory $configFactory,
    ) {
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return object[]|object
     */
    public function request(string $method, string $path, array $options = []): array|object
    {
        $client = $this->initClient();
        /** @var object[]|object $data */
        $data = $client->request($method, $path, $options)->getContent()
            |> json_decode(...);

        return $data;
    }

    private function initClient(): HttpClientInterface
    {
        if (is_null($this->client)) {
            $config = $this->configFactory->create();

            $this->client = HttpClient::create(
                new HttpOptions()
                    ->setBaseUri($config->baseUri)
                    ->toArray()
            );
        }

        return $this->client;
    }
}
