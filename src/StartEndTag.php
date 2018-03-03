<?php
/**
 * Implements the blueprint of a tag that expects both start & end tags.
 */
interface StartEndTag {
	/**
	 * Parses start tag.
	 *  
	 * Example: <std:foreach var="${ad.d}" key="K" value="V">
	 * 
	 * @param array(string=>string) $parameters
	 * @return string
	 */
	function parseStartTag($parameters=array());
	
	/**
	 * Parses end tag. 
	 * 
	 * Example: </std:foreach>
	 * 
	 * @return string
	 */
	function parseEndTag();
}