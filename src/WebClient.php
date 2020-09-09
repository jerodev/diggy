<?php

namespace Jerodev\Diggy;

use DOMDocument;
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

    public function get(string $url): SingleNode
    {
        return $this->request('GET', $url);
    }

    public function post(string $url): SingleNode
    {
        return $this->request('POST', $url);
    }

    private function request(string $method, string $url): SingleNode
    {
        $response = $this->client->request($method, $url);
        $content = $response->getBody()->getContents();

        $doc = new DOMDocument();
        $doc->loadHTML($content);

        return new SingleNode($doc);
    }
}
