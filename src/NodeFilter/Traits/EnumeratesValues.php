<?php

namespace Jerodev\Diggy\NodeFilter\Traits;

use Closure;
use DOMNode;
use DOMNodeList;
use Jerodev\Diggy\NodeFilter\SingleNode;

trait EnumeratesValues
{
    protected function internalEach(DOMNodeList $documents, $selector, ?Closure $closure = null): array
    {
        if (\is_string($selector)) {
            $documents = $this->querySelector($documents);
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
}