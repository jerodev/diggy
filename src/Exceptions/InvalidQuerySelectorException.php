<?php

namespace Jerodev\Diggy\Exceptions;

use Exception;
use Symfony\Component\CssSelector\Exception\SyntaxErrorException;

class InvalidQuerySelectorException extends Exception
{
    public function __construct(string $selector, SyntaxErrorException $previous)
    {
        parent::__construct("Invalid css query selector '{$selector}'.", 0, $previous);
    }
}
