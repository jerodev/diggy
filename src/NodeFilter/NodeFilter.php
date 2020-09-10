<?php

namespace Jerodev\Diggy\NodeFilter;

use Closure;

interface NodeFilter
{
    /**
     * Returns the number of nodes in the current collection.
     *
     * @return int
     */
    public function count(): int;

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
     * Indicates whether a certain node exists.
     *
     * @param string|null $selector
     * @return bool
     */
    public function exists(?string $selector = null): bool;

    /**
     * Filter the nodes by nodes that have a certain attribute.
     * Optionally, a required value can be set for this attribute.
     *
     * @param string $key
     * @param string|null $value
     * @return NodeFilter
     */
    public function hasAttribute(string $key, ?string $value = null): NodeFilter;

    /**
     * Filters the nodes with nodes that contain the given text.
     *
     * @param string|null $value The value to match against.
     * @param bool $trim Trim whitespaces and newlines from the node text.
     * @param bool $exact If false, the content should contain the give text.
     * @return NodeFilter
     */
    public function hasText(?string $value = null, bool $trim = true, bool $exact = false): NodeFilter;

    /**
     * Find one or more nodes using a css selector string.
     *
     * @param string $selector
     * @return NodeFilter
     */
    public function querySelector(string $selector): NodeFilter;

    /**
     * Returns the text content of the first node in the current collection.
     * Empty strings are converted to `null`.
     *
     * @return string|null
     */
    public function text(): ?string;

    /**
     * Returns the text content of all nodes in the current collection.
     *
     * @return string[]
     */
    public function texts(): array;

    /**
     * Filter the current nodes by their child nodes.
     * The closure gets a NodeFilter instance that can be used to define how the nodes should be filtered.
     *
     * @param Closure $closure
     * @return NodeFilter
     */
    public function whereHas(Closure $closure): NodeFilter;

    /**
     * Find one or more nodes using an xpath expression.
     *
     * @param string $selector
     * @return NodeFilter
     */
    public function xPath(string $selector): NodeFilter;
}
