<?php

namespace Jerodev\Diggy\NodeFilter;

use Closure;
use DOMDocument;
use DOMNode;
use DOMNodeList;

final class NodeCollection implements NodeFilter
{
    use Traits\EnumeratesValues;

    private DOMNodeList $documents;

    /**
     * @param DOMNode|DOMNodeList $nodes
     */
    public function __construct($nodes)
    {
        if ($nodes instanceof DOMNode) {
            $doc = new DOMDocument();
            $doc->loadHTML('<html></html>');
            $doc->importNode($nodes, true);

            $nodes = $doc->childNodes;
        }

        $this->documents = $nodes;
    }

    public function each($selector = null, ?Closure $closure = null): array
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
