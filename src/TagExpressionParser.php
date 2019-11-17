<?php
namespace Lucinda\Templating;

/**
 * Implements scalar expressions found in parsable tag attribute values that are going to be interpreted as PHP when response is displayed to client.
 */
class TagExpressionParser extends ExpressionParser
{
    /**
     * {@inheritDoc}
     * @see ExpressionParser::parseCallback()
     */
    protected function parseCallback($matches)
    {
        return $this->convertToVariable($matches[0]);
    }
}
