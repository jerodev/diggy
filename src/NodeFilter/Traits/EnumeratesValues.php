<?php

namespace Jerodev\Diggy\NodeFilter\Traits;

use Closure;
use DOMDocument;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use Jerodev\Diggy\NodeFilter\NodeCollection;
use Jerodev\Diggy\NodeFilter\NodeFilter;
use Jerodev\Diggy\NodeFilter\NullNode;
use Jerodev\Diggy\NodeFilter\SingleNode;
use Symfony\Component\CssSelector\CssSelectorConverter;

trait EnumeratesValues
{
    /**
     * @param DOMNodeList $documents
     * @param Closure|string $selector
     * @param Closure|null $closure
     * @return array
     */
    protected function internalEach(DOMNodeList $documents, $selector, ?Closure $closure = null): array
    {
        if (\is_string($selector)) {
            $documents = $this->internalQuerySelector($documents, $selector);
        } else if ($selector instanceof Closure) {
            $closure = $selector;
        }

        $values = [];
        foreach ($documents as $document) {
            \assert($document instanceof DOMNode);

            if ($closure instanceof Closure) {
                $values[] = $closure(SingleNode::fromDomNode($document));
            } else {
                $values[] = SingleNode::fromDomNode($document);
            }
        }

        return $values;
    }

    /**
     * @param DOMDocument|DOMNodeList $nodes
     * @param string $selector
     * @return NodeFilter
     */
    protected function internalQuerySelector($nodes, string $selector): NodeFilter
    {
        return $this->internalXpath(
            $nodes,
            (new CssSelectorConverter())->toXPath($selector)
        );
    }

    /**
     * @param DOMDocument|DOMNodeList $nodes
     * @param string $expression
     * @return NodeFilter
     */
    protected function internalXpath($nodes, string $expression): NodeFilter
    {
        $documentArray = [];
        if ($nodes instanceof DOMDocument) {
            $documentArray = [
                $nodes
            ];
        } else if ($nodes instanceof DOMNodeList) {
            foreach ($nodes as $node) {
                $doc = new DOMDocument();
                $doc->importNode($node, true);
                $documentArray[] = $doc;
            }
        }

        $newDoc = new DOMDocument();
        foreach ($documentArray as $document) {
            $xpath = new DOMXPath($document);
            $nodeList = $xpath->query($expression);

            foreach ($nodeList as $node) {
                $newDoc->appendChild($node);
            }
        }

        if ($newDoc->childNodes->length === 0) {
            return new NullNode();
        } elseif ($newDoc->childNodes->length === 1) {
            return SingleNode::fromDomNode($newDoc->firstChild);
        } else {
            return new NodeCollection($newDoc->childNodes);
        }
    }
}