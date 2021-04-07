<?php

namespace Jerodev\Diggy\NodeFilter;

use Closure;

interface NodeFilter
{
    /**
     * Get the value for the requested attribute of the first node in the current collection.
     *
     * @param string $name
     * @return string|null
     */
    public function attribute(string $name): ?string;

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
     * @param int|null $max Optional, The maximum number of nodes to loop over.
     * @return array
     */
    public function each($selector = null, ?Closure $closure = null, ?int $max = null): array;

    /**
     * Indicates whether a certain node exists.
     *
     * @param string|null $selector
     * @return bool
     */
    public function exists(?string $selector = null): bool;

    /**
     * Filter a list of nodes using a closure that returns a boolean.
     * The closure gets passed a node filter to test upon.
     *
     * @param Closure $closure
     * @return NodeFilter
     */
    public function filter(Closure $closure): NodeFilter;

    /**
     * Create a new collection of nodes only containing the first node of the current collection.
     *
     * @param string|null $selector
     * @return NodeFilter
     */
    public function first(?string $selector = null): NodeFilter;

    /**
     * Determine if the first element in the collection is a node with a determined name.
     *
     * @param string $nodeName
     * @return bool
     */
    public function is(string $nodeName): bool;

    /**
     * Create a new collection of nodes only containing the last node of the current collection.
     *
     * @param string|null $selector
     * @return NodeFilter
     */
    public function last(?string $selector = null): NodeFilter;

    /**
     * Returns the name of the current node.
     * If multiple nodes are selected, returns the name of the first node.
     *
     * @return string|null
     */
    public function nodeName(): ?string;

    /**
     * Create a new collection of nodes only containing the n'th node of the current collection.
     * The index of the first element is 0.
     * If the given index is out of bounds, a NullNode object will be returned.
     *
     * @param int $index
     * @param string|null $selector
     * @return NodeFilter
     */
    public function nth(int $index, ?string $selector = null): NodeFilter;

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
     * @param string|null $selector Optional selector for the node to get text from.
     * @return string|null
     */
    public function text(?string $selector = null): ?string;

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
     * Filter the nodes by nodes that have a certain attribute.
     * Optionally, a required value can be set for this attribute.
     *
     * @param string $key
     * @param string|null $value
     * @return NodeFilter
     */
    public function whereHasAttribute(string $key, ?string $value = null): NodeFilter;

    /**
     * Filters the nodes with nodes that contain the given text.
     *
     * @param string|null $value The value to match against.
     * @param bool $trim Trim whitespaces and newlines from the node text.
     * @param bool $exact If false, the content should contain the give text.
     * @return NodeFilter
     */
    public function whereHasText(?string $value = null, bool $trim = true, bool $exact = false): NodeFilter;

    /**
     * Find one or more nodes using an xpath expression.
     *
     * @param string $selector
     * @return NodeFilter
     */
    public function xPath(string $selector): NodeFilter;
}
