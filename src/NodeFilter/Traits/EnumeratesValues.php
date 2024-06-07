<?php

namespace Jerodev\Diggy\NodeFilter\Traits;

use Closure;
use DOMDocument;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use Jerodev\Diggy\Exceptions\InvalidQuerySelectorException;
use Jerodev\Diggy\NodeFilter\NodeCollection;
use Jerodev\Diggy\NodeFilter\NodeFilter;
use Jerodev\Diggy\NodeFilter\NullNode;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\CssSelector\Exception\SyntaxErrorException;

trait EnumeratesValues
{
    /**
     * @template T
     * @param DOMNodeList $nodes
     * @param Closure(NodeFilter, int):T|string|null $selector
     * @param Closure(NodeFilter, int):T|null $closure
     * @param int|null $max
     * @return ($closure is Closure ? array<T> : ($selector is Closure ? array<T> : array<NodeFilter>))
     */
    protected function internalEach(DOMNodeList $nodes, $selector = null, ?Closure $closure = null, ?int $max = null): array
    {
        if ($max !== null && $max <= 0) {
            return [];
        }

        if (\is_string($selector)) {
            return $this->internalQuerySelector($nodes, $selector)->each(null, $closure, $max);
        }

        if ($selector instanceof Closure) {
            $closure = $selector;
        }

        $values = [];
        $index = 0;
        foreach ($nodes as $node) {
            \assert($node instanceof DOMNode);

            if ($closure instanceof Closure) {
                $values[] = $closure(new NodeCollection($node), $index++);
            } else {
                $values[] = new NodeCollection($node);
            }

            if ($max !== null && --$max <= 0) {
                break;
            }
        }

        return $values;
    }

    /**
     * filter nodes directly on DOMNode level
     *
     * @param DOMNodeList $nodes
     * @param Closure(DOMNode):bool $callback
     * @return NodeFilter
     */
    protected function internalFilter(DOMNodeList $nodes, Closure $callback): NodeFilter
    {
        $doc = new DOMDocument();

        foreach ($nodes as $node) {
            if ($callback($node) && ($newNode = $doc->importNode($node, true))) {
                $doc->appendChild($newNode);
            }
        }

        if ($doc->childNodes->count() === 0) {
            return new NullNode();
        }

        return new NodeCollection($doc->childNodes);
    }

    /**
     * @param DOMNodeList $nodes
     * @param string $selector
     * @return NodeFilter
     */
    protected function internalQuerySelector(DOMNodeList $nodes, string $selector): NodeFilter
    {
        try {
            $xpath = (new CssSelectorConverter())->toXPath($selector);
        } catch (SyntaxErrorException $e) {
            throw new InvalidQuerySelectorException($selector, $e);
        }

        return $this->internalXpath($nodes, $xpath);
    }

    /**
     * @param DOMNodeList $nodes
     * @param string $expression
     * @return NodeFilter
     */
    protected function internalXpath(DOMNodeList $nodes, string $expression): NodeFilter
    {
        $newDoc = new DOMDocument();

        foreach ($nodes as $node) {
            $xpath = new DOMXPath($node->ownerDocument);
            $filteredNodes = $xpath->query($expression, $node);

            if ($filteredNodes === false) {
                continue;
            }

            foreach ($filteredNodes as $filteredNode) {
                if ($newNode = $newDoc->importNode($filteredNode, true)) {
                    $newDoc->appendChild($newNode);
                }
            }
        }

        if ($newDoc->childNodes->length === 0) {
            return new NullNode();
        } else {
            return new NodeCollection($newDoc->childNodes);
        }
    }
}
