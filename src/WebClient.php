<?php

namespace Jerodev\Diggy;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

final class WebClient
{
    private Client $client;

    public function __construct(array $guzzleConfig = [])
    {
        $config = \array_merge([
            RequestOptions::TIMEOUT => 30,
            RequestOptions::ALLOW_REDIRECTS => [
                'max' => 5,
            ],
        ], $guzzleConfig);

        $this->client = new Client($config);
    }

    public function request(string $method, string $url): Diggy
    {

    }
}
