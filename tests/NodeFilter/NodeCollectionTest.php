<?php

namespace Jerodev\Diggy\Tests\NodeFilter;

use DOMDocument;
use DOMNodeList;
use Jerodev\Diggy\NodeFilter\NodeCollection;
use Jerodev\Diggy\NodeFilter\NodeFilter;
use PHPUnit\Framework\TestCase;

final class NodeCollectionTest extends TestCase
{
    private NodeCollection $node;

    protected function setUp(): void
    {
        $this->node = new NodeCollection(
            $this->createDOMNodes()
        );
    }

    /** @test */
    public function it_should_check_if_node_exists(): void
    {
        $this->assertTrue($this->node->exists());
        $this->assertTrue($this->node->exists('li.third'));
        $this->assertFalse($this->node->exists('li.forth'));
    }

    /** @test */
    public function it_should_filter_has_attribute(): void
    {
        $nodes = $this->node
            ->querySelector('input')
            ->hasAttribute('required');

        $this->assertEquals(2, $nodes->count());
    }

    /** @test */
    public function it_should_filter_has_attribute_with_value(): void
    {
        $nodes = $this->node
            ->querySelector('input')
            ->hasAttribute('type', 'email');

        $this->assertEquals(1, $nodes->count());
    }

    /** @test */
    public function it_should_filter_has_text(): void
    {
        $nodes = $this->node
            ->querySelector('div')
            ->hasText();

        $this->assertEquals(2, $nodes->count());
    }

    /** @test */
    public function it_should_filter_has_text_value(): void
    {
        $nodes = $this->node
            ->querySelector('li')
            ->hasText('e');

        $this->assertEquals(2, $nodes->count());
    }

    /** @test */
    public function it_should_filter_has_text_value_exact(): void
    {
        $nodes = $this->node
            ->querySelector('li')
            ->hasText('one', true, true);

        $this->assertEquals(1, $nodes->count());
    }

    /** @test */
    public function it_should_filter_children_on_where_has(): void
    {
        $nodes = $this->node
            ->querySelector('div')
            ->whereHas(static fn (NodeFilter $f) => $f->querySelector('ul'));

        $this->assertEquals(1, $nodes->count());
        $this->assertStringStartsWith('one', \trim($nodes->text()));
    }

    /** @test */
    public function it_should_get_attribute_value(): void
    {
        $nodes = $this->node->querySelector('input[type=email]');

        $this->assertEquals('email', $nodes->getAttribute('type'));
        $this->assertNull($nodes->getAttribute('id'));
    }

    /** @test */
    public function it_should_get_text_from_element(): void
    {
        $this->assertEquals(
            'one',
            $this->node->querySelector('li')->text()
        );

        $this->assertEquals(
            'three',
            $this->node->querySelector('li.third')->text()
        );
    }

    /** @test */
    public function it_should_get_text_from_elements(): void
    {
        $this->assertEquals(
            ['one', 'two', 'three', 'four'],
            $this->node->querySelector('li')->texts()
        );

        $this->assertEquals(
            ['three'],
            $this->node->querySelector('li.third')->texts()
        );
    }

    /** @test */
    public function it_should_select_using_query_selector(): void
    {
        $nodes = $this->node->querySelector('li');

        $this->assertEquals(4, $nodes->count());
    }

    private function createDOMNodes(): DOMNodeList
    {
        $html = <<<'HTML'
        <!DOCTYPE html>
        <html>
            <body>
                <div>
                    <p>Intro</p>
                    <small class="info">Info</small>
                </div>
                <div>
                    <ul>
                        <li>one</li>
                        <li>two</li>
                        <li class="third">three</li>
                        <li>four</li>
                    </ul>
                </div>
                <div/>
                <form method="post">
                    Email: <input type="email" required />
                    Password: <input type="password" required />
                    telephone: <input type="text" />
                    Website: <input type="url" />
                </form>
            </body>
        </html>
        HTML;

        $doc = new DOMDocument();
        $doc->loadHTML($html);

        return $doc->childNodes;
    }
}
