<?php

namespace Jerodev\Diggy\NodeFilter;

/**
 * This class is returned from the fluent api when no nodes are left.
 * By doing this, the chain is not broken and the last function can return a default value.
 */
final class NullNode implements NodeFilter
{
    public function count(): int
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    public function each($selector = null, ?\Closure $closure = null): array
    {
        return [];
    }

    public function exists(?string $selector = null): bool
    {
        return false;
    }

    public function querySelector(string $selector): NodeFilter
    {
        return new self();
    }

    public function text(): ?string
    {
        return null;
    }

    public function texts(): array
    {
        return [];
    }

    public function xPath(string $selector): NodeFilter
    {
        return new self();
    }
}
