# Diggy web scraper

![PHP tests](https://github.com/jerodev/diggy/workflows/PHP%20tests/badge.svg) [![Latest Stable Version](https://poser.pugx.org/jerodev/diggy/v)](//packagist.org/packages/jerodev/diggy)

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

$socials = $page->first('#social')->querySelector('a span')->texts();
var_dump($socials);

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

### `attribute(string $name)`
Returns the value of the attribute of the first element in the collection if available.
```php
$nodes->attribute('href');
```

### `count()`
Returns the number of elements in the current node collection.
```php
$nodes->count();
```

### `each(string $selector, closure $closure, ?int $max = null)`
Loops over all dom elements in the current collection and executes a closure for each element.
The return value of this function is an array of values returned from the closure.
```php
$nodes->each('a', static function (NodeFilter $node) {
    return $a->attribute('href');
});
```

### `exists(?string $selector = null)`
Indicates if an element exists in the collection.
If a selector is given, the current nodes will first be filtered.
```php
$nodes->exists('a.active');
```

### `first(?string $selector = null)`
Returns the first element of the node collection.
If a selector is given, the current nodes will first be filtered.
```php
$nodes->first('a.active');
```

### `is(string $nodeName)`
Indicates if the first element in the current collection has a specified tag name.
```php
$nodes->is('div');
```

### `last(?string $selector = null)`
Returns the last element of the node collection.
If a selector is given, the current nodes will first be filtered.
```php
$nodes->last('a.active');
```

### `nodeName()`
Returns the tag name of the first element in the current node collection
```php
$nodes->nodeName();
```

### `nth(int $index, ?string $selector = null)`
Returns the nth element of the node collection, starting at `0`.
If a selector is given, the current nodes will first be filtered.
```php
$nodes->nth(1, 'a.active');
```

### `querySelector(string $selector)`
Finds all elements in the current node collection matching this css query selector.
```php
$nodes->querySelector('a.active');
```

### `text(?string $selector = null)`
Returns the inner text of the first element in the node collection.
If a selector is given, the current nodes will first be filtered.
```php
$nodes->text('p.description');
```

### `texts()`
Returns an array containing the inner text of every root element in the collection.
```php
$nodes->texts('nav > a');
```

### `whereHas(closure $closure)`
Filters the current node collection based on a given closure.
```php
$nodes->whereHas(static function (NodeFilter $node) {
    return $node->text() === 'foo';
});
```

### `whereHasAttribute(string $key, ?string $value = null)`
Filters the current node collection by the existence of a specific attribute.
If a value is given the collection is also filtered by the value of this attribute.
```php
$nodes->whereHasAttribute('href');
```

### `whereHasText(?string $value = null, bool $trim = true, bool $exact = false)`
Filters the current node collection by the existence of inner text.
Setting a value will also filter the nodes by the actual inner text based on `$trim` and `$exact`.

| option | function |
|---|---|
| `$trim` | Indicates the inner text value should be trimmed before matches with `$value`. |
| `$exact` | Indicates the inner text value should match `$value` exactly. |

```php
$nodes->whereHasText('foo');
```

### `xPath(string $selector)`
Finds all elements in the current node collection matching this xpath query selector.
```php
$nodes->xPath('//nav/a[@href]');
```
