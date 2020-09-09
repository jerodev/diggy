<?php

namespace Jerodev\Diggy\NodeFilter;

/**
 * This class is returned from the fluent api when no nodes were found.
 * By doing this, the chain is not broken and the last function can return a default value.
 */
final class NullNode implements NodeFilter
{
    /**
     * @inheritDoc
     */
    public function each($selector, ?\Closure $closure = null): array
    {
        return [];
    }

    public function querySelector(string $selector): NodeFilter
    {
        return new self();
    }

    public function xPath(string $selector): NodeFilter
    {
        return new self();
    }
}