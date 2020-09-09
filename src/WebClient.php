<?php

namespace Jerodev\Diggy;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Jerodev\Diggy\NodeFilter\SingleNode;

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

    public function request(string $method, string $url): SingleNode
    {

    }
}
