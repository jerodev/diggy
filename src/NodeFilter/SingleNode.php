<?php

namespace Jerodev\Diggy\NodeFilter;

use DOMDocument;
use DOMNode;
use DOMXPath;
use Symfony\Component\CssSelector\CssSelectorConverter;

final class SingleNode implements NodeFilter
{
    use Traits\EnumeratesValues;

    private DOMDocument $document;

    public function __construct(DOMDocument $document)
    {
        $this->document = $document;
    }

    public static function fromDomNode(DOMNode $node): self
    {
        $doc = new DOMDocument();
        $doc->importNode($node, true);

        return new self($doc);
    }

    public function each($selector, ?\Closure $closure = null): array
    {
        $doc = new DOMDocument();
        $doc->appendChild($this->document->parentNode);

        return $this->internalEach($doc->childNodes, $selector, $closure);
    }

    public function querySelector(string $selector): NodeFilter
    {
        return $this->xPath(
            (new CssSelectorConverter())->toXPath($selector)
        );
    }

    public function xPath(string $expression): NodeFilter
    {
        $xpath = new DOMXPath($this->document);
        $nodeList = $xpath->query($expression);

        if ($nodeList->count() === 0) {
            return new NullNode();
        } else if ($nodeList->count() === 1) {
            return new self($nodeList[0]);
        } else {
            return new NodeCollection($nodeList);
        }
    }
}