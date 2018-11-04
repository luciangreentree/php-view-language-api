<?php
namespace Lucinda\Templating;

require_once("StartTag.php");
require_once("StartEndTag.php");
require_once("TagExpressionParser.php");

/**
 * Implements operations common to all parsable (non-system) tags. All parsable tag classes must extend it.
 */
abstract class SystemTag {		
	/**
	 * Checks if tag attribute values contain expressions.
	 * 
	 * @param string $expression
	 * @return boolean
	 */
	protected function isExpression($expression) {
		return (strpos($expression,'${')!==false?true:false);
	}
	
	/**
	 * Converts expressions from tag attribute values into PHP.
	 * 
	 * @param string $expression
	 * @return string
	 */
	protected function parseExpression($expression) {
		$expressionObject = new TagExpressionParser();
		return $expressionObject->parse($expression);
	}
	
	/**
	 * Verifies if tag has required attributes defined. 
	 * 
	 * @param array(string=>string) $parameters
	 * @param array(string) $requiredParameters
	 * @throws ViewException If a required attribute is not found.
	 */
	protected function checkParameters($parameters, $requiredParameters) {
		foreach($requiredParameters as $name) {
			if(!isset($parameters[$name])) throw new ViewException("Tag requires attribute: ".$name);
		}
	}
}