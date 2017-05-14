<?php
/**
 * Implements the blueprint of a tag that expects no end tag.
 */
interface StartTag {
	/**
	 * Parses start tag.
	 *  
	 * Example:  <std:set name="VARNAME" value="EXPRESSION"/>
	 * 
	 * @param array(string=>string) $tblParameters
	 * @return string
	 */
	function parseStartTag($tblParameters=array());
}