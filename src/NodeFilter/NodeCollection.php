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

    public function attribute(string $name): ?string
    {
        $node = $this->nodes->item(0);
        if (! $node->hasAttributes()) {
            return null;
        }

        $attribute = $node->attributes->getNamedItem($name);
        if (\is_null($attribute)) {
            return null;
        }

        return $attribute->nodeValue;
    }

    public function count(): int
    {
        return $this->nodes->length;
    }

    public function each($selector = null, ?Closure $closure = null, ?int $max = null): array
    {
        return $this->internalEach($this->nodes, $selector, $closure, $max);
    }

    public function exists(?string $selector = null): bool
    {
        if (\is_null($selector)) {
            return true;
        }

        return $this->querySelector($selector)->exists();
    }

    public function first(?string $selector = null): NodeFilter
    {
        if (\is_null($selector)) {
            return new self($this->nodes->item(0));
        }

        return $this->querySelector($selector)->first();
    }

    public function is(string $nodeName): bool
    {
        return \strtolower($this->nodeName()) === \strtolower($nodeName);
    }

    public function last(?string $selector = null): NodeFilter
    {
        if (\is_null($selector)) {
            return new self($this->nodes->item($this->count() - 1));
        }

        return $this->querySelector($selector)->last();
    }

    public function nodeName(): ?string
    {
        return $this->nodes->item(0)->nodeName;
    }

    public function nth(int $index, ?string $selector = null): NodeFilter
    {
        if (\is_null($selector)) {
            if ($index > $this->count() - 1) {
                return new NullNode();
            }

            return new self($this->nodes->item($index));
        }

        return $this->querySelector($selector)->nth($index);
    }

    public function querySelector(string $selector): NodeFilter
    {
        return $this->internalQuerySelector($this->nodes, $selector);
    }

    public function text(?string $selector = null): ?string
    {
        if ($selector) {
            return $this->querySelector($selector)->text();
        }

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

    public function whereHasAttribute(string $key, ?string $value = null): NodeFilter
    {
        return $this->internalFilter($this->nodes, static function (DOMNode $node) use ($value, $key) {
            if (! $node->hasAttributes()) {
                return false;
            }

            $attribute = $node->attributes->getNamedItem($key);
            if (\is_null($attribute)) {
                return false;
            }

            return $value === null || $attribute->nodeValue === $value;
        });
    }

    public function whereHasText(?string $value = null, bool $trim = true, bool $exact = false): NodeFilter
    {
        return $this->internalFilter($this->nodes, static function (DOMNode $node) use ($exact, $trim, $value) {
            $text = $node->textContent;
            if ($text && $trim) {
                $text = \trim($text);
            }

            if ($value === null) {
                return ! empty($text);
            }

            if ($exact) {
                return $text === $value;
            }

            return \strpos($text, $value) !== false;
        });
    }

    public function xPath(string $expression): NodeFilter
    {
        return $this->internalXpath($this->nodes, $expression);
    }
}
