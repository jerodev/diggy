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
        return $this->internalQuerySelector($this->document, $selector);
    }

    public function xPath(string $expression): NodeFilter
    {
        return $this->internalXpath($this->document, $expression);
    }
}