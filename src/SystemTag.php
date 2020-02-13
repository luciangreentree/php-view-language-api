<?php
namespace Lucinda\Templating;

require_once("StartTag.php");
require("StartEndTag.php");
require("TagExpressionParser.php");

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
    protected function isExpression($expression)
    {
        return (strpos($expression, '${')!==false?true:false);
    }
    
    /**
     * Converts expressions from tag attribute values into PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function parseExpression($expression)
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
    protected function checkParameters($parameters, $requiredParameters)
    {
        foreach ($requiredParameters as $name) {
            if (!isset($parameters[$name])) {
                $tagName = get_class($this);
                preg_match("/Std(.*)Tag/", get_class($this), $matches);
                throw new ViewException("Tag '".strtolower($matches[1])."' requires attribute: ".$name);
            }
        }
    }
}
