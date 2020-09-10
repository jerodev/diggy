<?php

namespace Jerodev\Diggy\NodeFilter;

use Closure;

interface NodeFilter
{
    /**
     * Filter the current nodes and pass them to a defined closure.
     * The closure will be passed each DomNode as a SingleNode object and can return any value from it.
     *
     * @param Closure|string|null $selector
     * @param Closure|null $closure
     * @return array
     */
    public function each($selector = null, ?Closure $closure = null): array;

    /**
     * Find one or more nodes using a css selector string.
     *
     * @param string $selector
     * @return NodeFilter
     */
    public function querySelector(string $selector): NodeFilter;

    /**
     * Find one or more nodes using an xpath expression.
     *
     * @param string $selector
     * @return NodeFilter
     */
    public function xPath(string $selector): NodeFilter;
}
