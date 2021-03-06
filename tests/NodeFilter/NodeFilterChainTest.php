<?php

namespace Jerodev\Diggy\Tests\NodeFilter;

use DOMDocument;
use DOMNodeList;
use Generator;
use Jerodev\Diggy\NodeFilter\NodeCollection;
use Jerodev\Diggy\NodeFilter\NodeFilter;
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

    /**
     * @test
     * @dataProvider functionProvider
     * @param bool|string|null $expectedText
     * @param array $functions
     */
    public function it_should_chain_functions($expectedText, array $functions): void
    {
        $node = new NodeCollection($this->createDOMNodes());

        foreach ($functions as $function) {
            $node = $node->{$function[0]}(...$function[1] ?? []);
        }

        $this->assertEquals($expectedText, $node);
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

    public function functionProvider(): Generator
    {
        yield [
            'three',
            [
                ['querySelector', ['li']],
                ['whereHasAttribute', ['class', 'third']],
                ['text'],
            ],
        ];

        yield [
            'Info',
            [
                ['querySelector', ['div']],
                ['whereHas', [static fn (NodeCollection $n) => $n->querySelector('small')]],
                ['querySelector', ['small.info']],
                ['whereHasAttribute', ['class', 'info']],
                ['text'],
            ],
        ];

        yield [
            'password',
            [
                ['querySelector', ['form[method=post]']],
                ['querySelector', ['input']],
                ['whereHasAttribute', ['type']],
                ['nth', [1]],
                ['attribute', ['type']],
            ]
        ];

        yield [
            true,
            [
                ['querySelector', ['div p']],
                ['is', ['p']],
            ]
        ];

        yield [
            'two',
            [
                ['querySelector', ['ul li']],
                ['filter', [static fn (NodeFilter $node) => $node->text() === 'two']],
                ['text']
            ]
        ];
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
