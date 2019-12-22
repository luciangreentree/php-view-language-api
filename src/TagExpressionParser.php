<?php
namespace Lucinda\Templating;

/**
 * Implements scalar expressions found in parsable tag attribute values that are going to be interpreted as PHP when response is displayed to client.
 */
class TagExpressionParser extends ExpressionParser
{
    /**
     * For each macro-expression found, calls for its conversion to PHP and wraps it up as scriptlet.
     *
     * @param array $matches
     * @return string
     */
    protected function parseCallback(array $matches): string
    {
        return $this->convertToVariable($matches[0]);
    }
}
