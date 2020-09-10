<?php

namespace Jerodev\Diggy\NodeFilter;

use Closure;

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
    public function each($selector = null, ?Closure $closure = null): array
    {
        return [];
    }

    public function exists(?string $selector = null): bool
    {
        return false;
    }

    public function first(): NodeFilter
    {
        return $this;
    }

    public function getAttribute(string $name): ?string
    {
        return null;
    }

    public function last(): NodeFilter
    {
        return $this;
    }

    public function nth(int $index): NodeFilter
    {
        return $this;
    }

    public function querySelector(string $selector): NodeFilter
    {
        return $this;
    }

    public function text(?string $selector = null): ?string
    {
        return null;
    }

    public function texts(): array
    {
        return [];
    }

    public function whereHas(Closure $closure): NodeFilter
    {
        return $this;
    }

    public function whereHasAttribute(string $key, ?string $value = null): NodeFilter
    {
        return $this;
    }

    public function whereHasText(?string $value = null, bool $trim = true, bool $exact = false): NodeFilter
    {
        return $this;
    }

    public function xPath(string $selector): NodeFilter
    {
        return $this;
    }
}
