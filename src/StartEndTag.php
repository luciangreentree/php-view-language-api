<?php
/**
 * Implements the blueprint of a tag that expects both start & end tags.
 */
interface StartEndTag {
	/**
	 * Parses start tag.
	 *  
	 * Example: <standard:foreach var="${ad.d}" key="K" value="V">
	 * 
	 * @param array(string=>string) $tblParameters
	 * @return string
	 */
	function parseStartTag($tblParameters=array());
	
	/**
	 * Parses end tag. 
	 * 
	 * Example: </standard:foreach>
	 * 
	 * @return string
	 */
	function parseEndTag();
}