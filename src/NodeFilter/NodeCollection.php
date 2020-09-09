<?php

namespace Jerodev\Diggy\NodeFilter;

use Closure;
use DOMDocument;
use DOMNodeList;
use DOMXPath;
use Generator;
use Symfony\Component\CssSelector\CssSelectorConverter;

final class NodeCollection implements NodeFilter
{
    use Traits\EnumeratesValues;

    private DOMNodeList $documents;

    public function __construct(DOMNodeList $documents)
    {
        $this->documents = $documents;
    }

    public function each($selector, ?Closure $closure = null): array
    {
        return $this->internalEach($this->documents, $selector, $closure);
    }

    public function querySelector(string $selector): NodeFilter
    {
        return $this->internalQuerySelector($this->documents, $selector);
    }

    public function xPath(string $expression): NodeFilter
    {
        return $this->internalXpath($this->documents, $expression);
    }
}