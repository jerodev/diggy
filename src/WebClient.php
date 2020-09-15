<?php

namespace Jerodev\Diggy;

use DOMDocument;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Jerodev\Diggy\NodeFilter\NodeCollection;
use Jerodev\Diggy\NodeFilter\NodeFilter;
use Jerodev\Diggy\NodeFilter\NullNode;

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

        // https://www.php.net/manual/en/function.libxml-use-internal-errors.php
        \libxml_use_internal_errors(true);
    }

    public function get(string $url): NodeFilter
    {
        return $this->request('GET', $url);
    }

    public function post(string $url): NodeFilter
    {
        return $this->request('POST', $url);
    }

    private function request(string $method, string $url): NodeFilter
    {
        $response = $this->client->request($method, $url);
        $content = $response->getBody()->getContents();

        $doc = new DOMDocument();
        if ($doc->loadHTML($content)) {
            return new NodeCollection($doc->childNodes);
        }

        return new NullNode();
    }
}
