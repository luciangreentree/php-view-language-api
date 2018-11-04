<?php
namespace Lucinda\Templating;
/**
 * Implements the blueprint of a tag that expects no end tag.
 */
interface StartTag {
	/**
	 * Parses start tag.
	 *  
	 * Example:  <:set name="VARNAME" value="EXPRESSION"/>
	 * 
	 * @param array(string=>string) $parameters
	 * @return string
	 */
	function parseStartTag($parameters=array());
}