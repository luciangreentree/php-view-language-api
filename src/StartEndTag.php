<?php
namespace Lucinda\Templating;
/**
 * Implements the blueprint of a tag that expects both start & end tags.
 */
interface StartEndTag {
	/**
	 * Parses start tag.
	 *  
	 * Example: <:foreach var="${ad.d}" key="K" value="V">
	 * 
	 * @param string[string] $parameters
	 * @return string
     * @throws ViewException If required parameters aren't supplied
	 */
	function parseStartTag($parameters=array());
	
	/**
	 * Parses end tag. 
	 * 
	 * Example: </:foreach>
	 * 
	 * @return string
	 */
	function parseEndTag();
}