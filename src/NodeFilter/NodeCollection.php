<?php

namespace Jerodev\Diggy\NodeFilter;

use Closure;
use DOMDocument;
use DOMNode;
use DOMNodeList;
use Exception;

final class NodeCollection implements NodeFilter
{
    use Traits\EnumeratesValues;

    private DOMNodeList $nodes;

    /**
     * @param DOMNode|DOMNodeList $nodes
     *
     * @throws Exception When node could not be imported into a new document.
     */
    public function __construct($nodes)
    {
        if ($nodes instanceof DOMNode) {
            $doc = new DOMDocument();
            if ($node = $doc->importNode($nodes, true)) {
                $doc->appendChild($node);
            } else {
                throw new Exception("Node with type '{$nodes->nodeType}' could not be imported.");
            }

            $nodes = $doc->childNodes;
        }

        $this->nodes = $nodes;
    }

    public function count(): int
    {
        return $this->nodes->length;
    }

    public function each($selector = null, ?Closure $closure = null): array
    {
        return $this->internalEach($this->nodes, $selector, $closure);
    }

    public function querySelector(string $selector): NodeFilter
    {
        return $this->internalQuerySelector($this->nodes, $selector);
    }

    public function text(): ?string
    {
        return $this->nodes->item(0)->textContent;
    }

    public function texts(): array
    {
        return $this->each(static fn (NodeCollection $n) => $n->text());
    }

    public function xPath(string $expression): NodeFilter
    {
        return $this->internalXpath($this->nodes, $expression);
    }
}
