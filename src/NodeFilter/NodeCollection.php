<?php

namespace Jerodev\Diggy\NodeFilter;

use Closure;
use DOMDocument;
use DOMNodeList;
use DOMXPath;
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
        return $this->xPath(
            (new CssSelectorConverter())->toXPath($selector)
        );
    }

    public function xPath(string $expression): NodeFilter
    {
        $newDoc = new DOMDocument();

        foreach ($this->documents as $document) {
            $xpath = new DOMXPath($document);
            $nodeList = $xpath->query($expression);

            foreach ($nodeList as $node) {
                $newDoc->appendChild($node);
            }
        }

        if ($newDoc->childNodes->length === 0) {
            return new NullNode();
        } else {
            return new self($newDoc->childNodes);
        }
    }
}