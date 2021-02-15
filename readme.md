# Diggy web scraper

![PHP tests](https://github.com/jerodev/diggy/workflows/PHP%20tests/badge.svg)

Diggy is a simple wrapper around the [PHP DOM extension](https://www.php.net/manual/en/intro.dom.php) that allow finding
elements using simple query selectors and fail proof chaining.

## Requirements

 - PHP 7.4 or PHP 8.0

## Getting started

Diggy includes a simple webclient that uses Guzzle under the hood to download a page and return a `NodeCollection`
object. However, you can use any webclient you prefer and pass a `DOMNode` or `DOMNodeList` object to the
`NodeCollection` constructor.

```php
$client = new \Jerodev\Diggy\WebClient();
$page = $client->get('https://www.deviaene.eu/');
var_dump($page->first('#social')->querySelector('a span')->texts());

//    [
//        'GitHub',
//        'Twitter',
//        'Email',
//        'LinkedIn',
//    ]
```

## Available functions
These are the available functions on a `NodeCollection` object. All functions that do not return a native value can be
chained without having to worry if there are nodes in the collection or not.

| Function  | Description | Example |
| ------------- | ------------- | ------------- |
| `attribute(string $name)` | Returns the value of the attribute if available. | `$nodes->attribute('href')` |
| `count()` | Returns the number of elements in the current node collection. | `$nodes->count()` |
| `each()` | Loops over all dom elements in the current collection and returns an array of values returned by the callback function. | `$nodes->each('a', static fn (NodeFilter $a) => $a->attribute('href'))` |
| `exists(?string $selector)` | Indicates if any element exists in the collection. If a `$selector` is provided, the collection is first filtered by this selector. | `$nodes->exists('a.active')` |
| `first(?string $selector)` | Create a new node collection with only the first element of the current collection. If a `$selector` is provided, the collection is first filtered by this selector. | `$nodes->first('a.active')` |
| `is(string $nodeName)` | Indicates if the first node in the collection is a node with a specific tag name. | `$nodes->is('div')` |
| `last(?string $selector)` | Create a new node collection with only the last element of the current collection. If a `$selector` is provided, the collection is first filtered by this selector. | `$nodes->last('a.active')` |
| `nodeName()` | Returns the tag name of the first element in the collection. | `$nodes->nodeName()` |
| `nth(int $index, ?string $selector)` | Create a new node collection with only the nth element of the current collection starting at 0. If a `$selector` is provided, the collection is first filtered by this selector. | `$nodes->nth(1, 'a.active')` |
| `querySelector(string $selector)` | Filter the current node collection by a given css selector. | `$nodes->querySelector('.active')` |
| `text(?string $selector)` | Returns the inner text from the first element in the collection. If a `$selector` is provided, the collection is first filtered by this selector. | `$nodes->text()` |
| `texts()` | Returns an array of strings with all inner texts of the nodes in the collection. | `$nodes->text()` |
| `whereHas(closure $closure)` | Filters the node collection to elements that pass the given closure. | `$nodes->whereHas(static fn (NodeFilter $node) => $node->text() === 'foo')` |
| `whereHasAttribute(string $key, ?string $value)` | Filters the node collection by elements that have a certain attribute. If a `$value` is provided the collection is also filtered by elements where the attribute has this value. | `$nodes->whereHasAttribute('href')` |
| `whereHasText(string $value)` | Filters the node collection by elements that have a value in their inner text. | `$nodes->whereHasText('foo')` |
| `xPath(string $selector)` | Filter the current node collection by a given xpath selector. | `$nodes->xPath('//nav/a[@href]')` |
