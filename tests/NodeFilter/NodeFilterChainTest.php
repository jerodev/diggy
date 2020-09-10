<?php

namespace Jerodev\Diggy\Tests\NodeFilter;

use DOMDocument;
use DOMNodeList;
use Jerodev\Diggy\NodeFilter\NodeCollection;
use PHPUnit\Framework\TestCase;

final class NodeFilterChainTest extends TestCase
{
    /** @test */
    public function it_should_allow_chaining_query_selectors(): void
    {
        $node = new NodeCollection($this->createDOMNodes());

        $text = $node
            ->querySelector('div')
            ->querySelector('ul')
            ->querySelector('li.third')
            ->text();

        $this->assertEquals('three', $text);
    }

    /** @test */
    public function it_should_not_break_on_empty_chain_value(): void
    {
        $node = new NodeCollection($this->createDOMNodes());

        $text = $node
            ->querySelector('div')
            ->querySelector('p')
            ->querySelector('strong')
            ->text();

        $this->assertNull($text);
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
            </body>
        </html>
        HTML;

        $doc = new DOMDocument();
        $doc->loadHTML($html);

        return $doc->childNodes;
    }
}
