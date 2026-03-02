<?php

namespace App\Infrastructure\Integration\JSONPlaceholder;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\HttpOptions;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class JSONPlaceholderClientService implements JSONPlaceholderClient
{
    private const string BASE_URI = 'https://jsonplaceholder.typicode.com';

    private HttpClientInterface $client;

    public function __construct()
    {
        $this->client = HttpClient::create(
            new HttpOptions()
                ->setBaseUri(self::BASE_URI)
                ->toArray()
        );
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return object[]|object
     */
    public function request(string $method, string $path, array $options = []): array|object
    {
        /** @var object[]|object $data */
        $data = $this->client->request($method, $path, $options)->getContent()
            |> json_decode(...);

        return $data;
    }
}
