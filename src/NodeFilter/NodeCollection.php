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

    public function exists(?string $selector = null): bool
    {
        if (\is_null($selector)) {
            return true;
        }

        return $this->querySelector($selector)->exists();
    }

    public function querySelector(string $selector): NodeFilter
    {
        return $this->internalQuerySelector($this->nodes, $selector);
    }

    public function text(): ?string
    {
        $content = $this->nodes->item(0)->textContent;
        if (empty($content)) {
            return null;
        }

        return $content;
    }

    public function texts(): array
    {
        return $this->each(static fn (NodeCollection $n) => $n->text());
    }

    public function whereHas(Closure $closure): NodeFilter
    {
        $newDoc = new DOMDocument();

        foreach ($this->nodes as $node) {
            $filter = $closure(new NodeCollection($node));

            if ($filter->exists() && ($newNode = $newDoc->importNode($node, true))) {
                $newDoc->appendChild($newNode);
            }
        }

        return new NodeCollection($newDoc->childNodes);
    }

    public function xPath(string $expression): NodeFilter
    {
        return $this->internalXpath($this->nodes, $expression);
    }
}
