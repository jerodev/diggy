<?php

namespace Jerodev\Diggy\Tests\NodeFilter;

use Jerodev\Diggy\NodeFilter\NodeCollection;
use Jerodev\Diggy\NodeFilter\NullNode;
use PHPUnit\Framework\TestCase;

final class NullNodeTest extends TestCase
{
    private NullNode $node;

    protected function setUp(): void
    {
        $this->node = new NullNode();
    }

    /** @test */
    public function it_should_return_empty_array_on_each(): void
    {
        $this->assertEquals(
            [],
            $this->node->each('.foo', static fn (NodeCollection $n) => $n)
        );
    }

    /** @test */
    public function it_should_return_empty_array_on_texts(): void
    {
        $this->assertEquals(
            [],
            $this->node->texts()
        );
    }

    /** @test */
    public function it_should_return_false_on_exists(): void
    {
        $this->assertFalse($this->node->exists());
    }

    /** @test */
    public function it_should_return_null_node_on_query_selector(): void
    {
        $this->assertInstanceOf(
            NullNode::class,
            $this->node->querySelector('.foo')
        );
    }

    /** @test */
    public function it_should_return_null_node_on_xpath(): void
    {
        $this->assertInstanceOf(
            NullNode::class,
            $this->node->xPath('.foo')
        );
    }

    /** @test */
    public function it_should_return_null_on_text(): void
    {
        $this->assertNull(
            $this->node->text()
        );
    }
}
