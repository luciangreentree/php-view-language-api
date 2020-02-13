<?php
namespace Lucinda\Templating;

/**
 * Implements operations common to all parsable (non-system) tags. All parsable tag classes must extend it.
 */
abstract class SystemTag
{
    /**
     * Checks if tag attribute values contain expressions.
     *
     * @param string $expression
     * @return boolean
     */
    protected function isExpression(string $expression): bool
    {
        return (strpos($expression, '${')!==false?true:false);
    }
    
    /**
     * Converts expressions from tag attribute values into PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function parseExpression(string $expression): string
    {
        $expressionObject = new TagExpressionParser();
        return $expressionObject->parse($expression);
    }
    
    /**
     * Verifies if tag has required attributes defined.
     *
     * @param string[string] $parameters
     * @param string[] $requiredParameters
     * @throws ViewException If a required attribute is not found.
     */
    protected function checkParameters(array $parameters, array $requiredParameters): void
    {
        foreach ($requiredParameters as $name) {
            if (!isset($parameters[$name])) {
                preg_match("/Std(.*)Tag/", get_class($this), $matches);
                throw new ViewException("Tag '".strtolower($matches[1])."' requires attribute: ".$name);
            }
        }
    }
}
