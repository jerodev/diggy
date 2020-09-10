<?php

namespace Jerodev\Diggy\Tests\NodeFilter;

use DOMDocument;
use DOMNodeList;
use Jerodev\Diggy\NodeFilter\NodeCollection;
use PHPUnit\Framework\TestCase;

class NodeCollectionTest extends TestCase
{
    private NodeCollection $node;

    protected function setUp(): void
    {
        $this->node = new NodeCollection(
            $this->createDOMNodes()
        );
    }

    /** @test */
    public function it_should_select_using_query_selector(): void
    {
        $nodes = $this->node->querySelector('li');

        $this->assertCount(4, $nodes->each());
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
